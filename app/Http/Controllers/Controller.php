<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Section;
use App\Models\Schedules;
use App\Models\Submissions;
use App\Models\Attendances;
use App\Models\Std_Section;
use App\Models\Languages_Type;
use Illuminate\Http\Request;
use App\Events\SectionManagerEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Services\Judge0Service;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Event;
use App\Exports\AttendanceExport;
use App\Exports\MarksExport;
use App\Imports\UsersImport;
use App\Imports\SectionsImport;
use App\Imports\StdSectionImport;
use App\Imports\SchedulesImport;
use Maatwebsite\Excel\Facades\Excel;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function index()
    {
        $user = Auth::user();
        if ($user->permission_id == 100) {
            $users = User::where('permission_id','<=',10)->get();
            return view('admin.admin_dashboard')->with(['users' => $users]);
        }
        if ($user->permission_id == 10) {
            $sections = Section::where([['instructor_id', $user->id]])->get();
            return view('instructor_dashboard')->with(['sections' => $sections]);
        } else {
            $stdSections = Std_Section::where([['std_id', $user->id], ['semester_id', $user->semester_id]])->get();
            return view('student_dashboard')->with(['stdSections' => $stdSections]);
        }
    }
    public function section($sectionId,$scheduleId=0)
    {
        //$dt = Carbon::parse('2012-10-5 23:26:11.123789');
        if (filter_var($sectionId, FILTER_VALIDATE_INT) === false) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'Invalid Section Id, Please Try Again']);
        }
        if (filter_var($scheduleId, FILTER_VALIDATE_INT) === false) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'Invalid Schedule Id, Please Try Again']);
        }
        $user = Auth::user();

        if ($user->permission_id == 10) {
            $sectionData1 = Section::with([
                'instructor:id,name,email',
                'schedules' => ['week:id,name','submissions','attendances'],
                'students:id,std_id,section_id' => ['student:id,name,email'],
                'course:id,name,lang_id' => ['pro_lang:id,lang_id,name']
            ])->where([['id',$sectionId],['instructor_id', $user->id]])->get()->first();
            if($scheduleId>0){
                Schedules::where([['id',$scheduleId],['section_id',$sectionData1->id]])->update(['status_id' => 3]);
                $sectionData1 = Section::with([
                    'instructor:id,name,email',
                    'schedules' => ['week:id,name','submissions','attendances'],
                    'students:id,std_id,section_id' => ['student:id,name,email'],
                    'course:id,name,lang_id' => ['pro_lang:id,lang_id,name']
                ])->where([['id',$sectionId],['instructor_id', $user->id]])->get()->first();
                foreach ($sectionData1->students as $student) {
                    $data = [
                        "id" => $scheduleId,
                        "userId" => $student->std_id,
                        "sectionId" => $sectionData1->id,
                        "sectionNmae" => $sectionData1->name,
                        "subject" => $sectionData1->course->name,
                        "url" => url('section')."/".$sectionData1->id,
                        "type" => 'endSession',
                    ];
                    event(new SectionManagerEvent($data));
                }
            }
            return view('instructor_schedule')->with(['sectionData' => $sectionData1]);
        } else {
            $sectionData = Section::with([
                'instructor:id,name,email',
                'schedules' => ['week:id,name'],
                'course:id,name,lang_id' => ['pro_lang:id,lang_id,name']
            ])->find($sectionId);
            return view('schedule')->with(['sectionData' => $sectionData]);
        }
        
    }
    public function attendances($sectionId)
    {
        if (filter_var($sectionId, FILTER_VALIDATE_INT) === false) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'Invalid Section Id, Please Try Again']);
        }

        $user = Auth::user();

        if ($user->permission_id == 10) {
            $section = Section::with([
                'instructor:id,name,email',
                'schedules' => ['week:id,name','attendances'],
                'students:id,std_id,section_id' => ['student:id,name,email'],
            ])->where([['id',$sectionId],['instructor_id', $user->id]])->get()->first();
          return Excel::download(new AttendanceExport($section), 'Section '.$section->name.'-Attendances.xlsx');
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function marks($sectionId)
    {
        if (filter_var($sectionId, FILTER_VALIDATE_INT) === false) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'Invalid Section Id, Please Try Again']);
        }

        $user = Auth::user();

        if ($user->permission_id == 10) {
            $section = Section::with([
                'instructor:id,name,email',
                'schedules' => ['week:id,name','submissions'],
                'students:id,std_id,section_id' => ['student:id,name,email'],
            ])->where([['id',$sectionId],['instructor_id', $user->id]])->get()->first();


          return Excel::download(new MarksExport($section), 'Section '.$section->name.'-Marks.xlsx');
        } 
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function getUsers()
    {
        $user = Auth::user();
        if ($user->permission_id == 100) {
            $users = User::where('permission_id','<=',10)->get();
            return view('admin.users')->with(['users' => $users]);
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function importUsers(Request $request) 
    {
        $user = Auth::user();
        
        $request->validate([
           'user_file' => 'required|max:1000|mimes:xlsx'
        ]);
        if ($user->permission_id == 100) {
            Excel::import(new UsersImport, $request->user_file);
            return redirect()->back()->with('success_message', 'Users imported successfully!');
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function getSections()
    {
        $user = Auth::user();
        if ($user->permission_id == 100) {
            $sections = Section::get();
            return view('admin.sections')->with(['sections' => $sections]);
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function importSections(Request $request) 
    {
        $user = Auth::user();
        
        $request->validate([
           'sections_file' => 'required|max:1000|mimes:xlsx'
        ]);
        if ($user->permission_id == 100) {
            Excel::import(new SectionsImport, $request->sections_file);
            return redirect()->back()->with('success_message', 'Sections imported successfully!');
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function getStudents()
    {
        $user = Auth::user();
        if ($user->permission_id == 100) {
            $students = Std_Section::get();
            return view('admin.students')->with(['students' => $students]);
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function importStdSection(Request $request) 
    {
        $user = Auth::user();
        
        $request->validate([
           'stds_file' => 'required|max:1000|mimes:xlsx'
        ]);
        if ($user->permission_id == 100) {
            Excel::import(new StdSectionImport, $request->stds_file);
            return redirect()->back()->with('success_message', 'Students imported to sections successfully!');
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function getSchedules()
    {
        $user = Auth::user();
        if ($user->permission_id == 100) {
            $schedules = Schedules::get();
            return view('admin.schedules')->with(['schedules' => $schedules]);
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    public function importSchedules(Request $request) 
    {
        $user = Auth::user();
        
        $request->validate([
           'schedules_file' => 'required|max:1000|mimes:xlsx'
        ]);
        if ($user->permission_id == 100) {
            Excel::import(new SchedulesImport, $request->schedules_file); 
            return redirect()->back()->with('success_message', 'Schedules imported successfully!');
        }
        Session::flash('message', 'You don\'t have permission to open this page');
        return redirect()->back(); 
    }
    
    public function liveSession($sectionId)
    {
        if (filter_var($sectionId, FILTER_VALIDATE_INT) === false) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'Invalid Section Id, Please Try Again']);
        }

        $user = Auth::user();

        if ($user->permission_id == 10) {
            $section = Section::find($sectionId);
            if($section){
                Schedules::create([
                    'section_id' => $section->id,
                    'week_id' => 1,
                    'date' => Carbon::now(),
                    'hrs' => 2,
                    'status_id' => 1,
                ]);
                Session::flash('live_session', 'The live session is ready to use; you can start it now.');
                return redirect()->back();
            }
            Session::flash('message', 'Invalid Data, Please Try Again');
            return redirect()->back();
        } 
        
    }
    public function schedule($scheduleId,$type='attendances')
    {
        if (filter_var($scheduleId, FILTER_VALIDATE_INT) === false) {
            return redirect()->back()->with(['alert_color' => 'danger', 'message' => 'Invalid Schedule Id, Please Try Again']);
        }
        $user = Auth::user();
        if($user->permission_id == 10){
            $schedule = Schedules::find($scheduleId);
            if($schedule){
                if($schedule->section->instructor_id == $user->id){
                    if($type=='attendances')
                        return view('instructor_attendances')->with(['schedule' => $schedule]);
                    if($type=='submissions')
                        return view('instructor_submissions')->with(['schedule' => $schedule]);
                }
                return redirect()->back()->with(['alert_color' => 'danger', 'message' => 'You don\'t have permission to open this page']);
            }
            return redirect()->back()->with(['alert_color' => 'danger', 'message' => 'Invalid Schedule Id, Please Try Again']);
        }
        return redirect()->back()->with(['alert_color' => 'danger', 'message' => 'You don\'t have permission to open this page']);
    }
    public function viewCode($id)
    {
        //$dt = Carbon::parse('2012-10-5 23:26:11.123789');
        if (filter_var($id, FILTER_VALIDATE_INT) === false) {
            return redirect('home')->with(['alert_color' => 'danger', 'message' => 'Invalid Submission Id, Please Try Again']);
        }
        $user = Auth::user();

        if ($user->permission_id == 10) {
            $codeData = Submissions::with([
                'schedule',
                'student:id,name,email',
            ])->find($id);
        } else {
            $codeData = Submissions::with([
                'schedule',
                'student:id,name,email',
            ])->where('std_id',$user->id)->find($id);
        }
        $status='disabled';
        if($codeData){
            $date = Carbon::parse($codeData->schedule->date);
            $dateNow = Carbon::now();
            $newDate = Carbon::parse($codeData->schedule->date)->addHours($codeData->schedule->hrs * 1 + 24);
            if ($dateNow->between($date, $newDate) && $codeData->status < 2) {
                $status='';
                return view('code')->with(['codeData' => $codeData,'schedule'=>'','status'=>$status]);
            }
        }
        return view('code')->with(['codeData' => $codeData,'schedule'=>'','status'=>$status]);
    }
    public function newCode($scheduleId)
    {
        $user = Auth::user();
        if ($user->permission_id == 10) {
            Session::flash('message', 'You dont');
            return redirect()->back();
        } else {
            if (filter_var($scheduleId, FILTER_VALIDATE_INT) === false) {
                return redirect('home')->with(['alert_color' => 'danger', 'message' => 'Invalid Schedule Id, Please Try Again']);
            }
            $schedule = Schedules::find($scheduleId);
            if($schedule){
                $date = Carbon::parse($schedule->date);
                $dateNow = Carbon::now();
                $newDate = Carbon::parse($schedule->date)->addHours($schedule->hrs * 1 + 24);
                if ($dateNow->between($date, $newDate)) {
                    return view('code')->with(['codeData' => '','schedule'=>$schedule,'status'=>'']);
                }
            }
            Session::flash('message', 'Sorry, submissions have expired');
            return redirect()->back();
        }   
    }
    public function runCode(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editorData'     => ['required', 'string'],
            'schedule'      => ['required', 'integer'],
        ]);
        $schedule = Schedules::find($request->schedule);
        if($schedule){
            $lang = $schedule->section->course->pro_lang->lang_id;
            if ($user->permission_id == 10) {
                $request->validate([
                    'stdId'      => ['required', 'integer'],
                ]);
                
                $stdSubmission = $schedule->submissions->where('std_id',$request->stdId)->first();
                    if(empty($stdSubmission))
                        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
                    $outputData = $this->codeCompile($request->editorData,$lang);
                    if(!$outputData[0])
                        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
                    $submission = Submissions::updateOrCreate(
                        ['schedule_id' => $request->schedule,'std_id'=>$stdSubmission->std_id],
                        ['code' => $request->editorData, 'output' => $outputData[1]]
                    );
                    return Response::json($submission,200);
            } else {
                $submission = $schedule->submissions->where('std_id',$user->id)->first();
                    if($submission)
                    if($submission->status == 2)
                        return Response::json(['message'=>'Sorry, Your submission is confirmed'],422);
                $date = Carbon::parse($schedule->date);
                $dateNow = Carbon::now();
                $newDate = Carbon::parse($schedule->date)->addHours($schedule->hrs * 1 + 24);
                if ($dateNow->between($date, $newDate)) {
                    $outputData = $this->codeCompile($request->editorData,$lang);
                    if(!$outputData[0])
                        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
                    $submission = Submissions::updateOrCreate(
                        ['schedule_id' => $request->schedule,'std_id'=>$user->id],
                        ['code' => $request->editorData, 'output' => $outputData[1],'status'=>0]
                    );
                    if($submission->req_view==1){
                        $data = [
                            "id" => $submission->schedule->id,
                            "userId" => $submission->schedule->section->instructor_id,
                            "stdId" => $submission->std_id,
                            "submissionId" => $submission->id,
                            "result" => $submission,
                            "name"  => $user->name,
                            "nameMess"=> $user->name .": view completed",
                            "type" => 'viewCode',
                        ];
                        event(new SectionManagerEvent($data));
                    }
                    return Response::json($submission,200);
                }
                return Response::json(['message'=>'Sorry, submissions have expired'],422);
            }
        }
        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
    }
    public function submitCode(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editorData'     => ['required', 'string'],
            'schedule'      => ['required', 'integer'],
        ]);

        if ($user->permission_id == 1) {
            $schedule = Schedules::find($request->schedule);
            if($schedule){
                $submission = $schedule->submissions->where('std_id',$user->id)->first();
                    if($submission)
                    if($submission->status == 2)
                        return Response::json(['message'=>'Sorry, Your submission is confirmed'],422);
                $date = Carbon::parse($schedule->date);
                $dateNow = Carbon::now();
                $newDate = Carbon::parse($schedule->date)->addHours($schedule->hrs * 1 + 24);
                if ($dateNow->between($date, $newDate)) {
                    $submissionUp = Submissions::where(['schedule_id' => $request->schedule,'std_id'=>$user->id])->update(['status'=>1]);
                   
                        $data = [
                            "id" => $schedule->id,
                            "userId" => $schedule->section->instructor_id,
                            "stdId" => $user->id,
                            "submissionId" => $submission->id,
                            "result" => $submission->results,
                            "name"  => $user->name,
                            "nameMess"=> $user->name .": submission completed",
                            "type" => 'stdSubmission',
                        ];
                        event(new SectionManagerEvent($data));
                   
                    return Response::json($submissionUp,200);
                }
                return Response::json(['message'=>'Sorry, submissions have expired'],422);
            }
            return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
        }
        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
    }
    
    public function startSession($scheduleId)
    {
        if (filter_var($scheduleId, FILTER_VALIDATE_INT) === false) {
            return redirect('home')->with(['alert_color' => 'danger', 'message' => 'Invalid Schedule Id, Please Try Again']);
        }
        $user = Auth::user();
        $schedule = Schedules::find($scheduleId);
        if($user->permission_id == 10){
            if($schedule){
                $date = Carbon::parse($schedule->date);
                $dateNow = Carbon::now();
                $newDate = Carbon::parse($schedule->date)->addHours($schedule->hrs * 1);
                if ($dateNow->between($date, $newDate) && $schedule->section->instructor_id==$user->id) {
                    $schedule->status_id = 2;
                    $schedule->date = $dateNow;
                    $schedule->save();
                        foreach ($schedule->section->students as $student) {
                            $data = [
                                "id" => $schedule->id,
                                "userId" => $student->std_id,
                                "sectionId" => $schedule->section->id,
                                "sectionNmae" => $schedule->section->name,
                                "subject" => $schedule->section->course->name,
                                "url" => url('start-session')."/".$schedule->id,
                                "type" => 'startSession',
                            ];
                            event(new SectionManagerEvent($data));
                        }
                    return view('instructor_session')->with(['schedule'=>$schedule]);
                }
            }
        }else{
            if($schedule){
                $date = Carbon::parse($schedule->date);
                $dateNow = Carbon::now();
                $newDate = Carbon::parse($schedule->date)->addHours($schedule->hrs * 1);
                if ($dateNow->between($date, $newDate)) {
                    if($schedule->status_id == 1){
                        Session::flash('message', 'Sorry, Session not yet started');
                        return redirect()->back();
                    }
                    if($schedule->status_id == 2){
                        $present = Attendances::updateOrCreate(
                            ['schedule_id' => $schedule->id,'std_id'=>$user->id],
                            ['updated_at'=>$dateNow]
                        );
                        $data = [
                            "id" => $schedule->id,
                            "userId" => $schedule->section->instructor_id,
                            "stdId" => $user->id,
                            "name"  => $user->name,
                            "nameMess"=> $user->name ." is present",
                            "type" => 'stdAttendance',
                        ];
                        event(new SectionManagerEvent($data));
                        return view('section_session')->with(['schedule'=>$schedule]);
                    }
                    if($schedule->status_id == 3){
                        return redirect('/section/{{$schedule->section_id}}');
                    }
                }
            }
        }  
        Session::flash('message', 'Sorry, Session have expired');
            return redirect()->back();
    }
    public function getCode(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'viewCode'  => ['integer'],
            'std'       => ['required', 'integer'],
            'schedule'  => ['required', 'integer'],
        ]);

        if ($user->permission_id == 10) {
            if($request->viewCode == 1){
                $data = [
                    "id" => $request->schedule,
                    "userId" => $request->std,
                    "instId" => $user->id,
                    "type" => 'viewCode',
                ];
                event(new SectionManagerEvent($data));
                $submission = Submissions::where([['std_id',$request->std],['schedule_id',$request->schedule]])->update(['req_view' => 1]);
            }
            $submission = Submissions::where([['std_id',$request->std],['schedule_id',$request->schedule]])->get()->first();

            return Response::json($submission,200);
        }  
        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
        
    }
    public function result(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'submition'     => ['required', 'integer'],
            'result'      => ['required', 'integer'],
        ]);

        if ($user->permission_id == 10) {
            $submission = Submissions::where([['id',$request->submition]])->update(['results' => $request->result,'status'=>2]);
            $submissionData = Submissions::find($request->submition);
            if($submissionData){
                $data = [
                    "id" => $submissionData->schedule_id,
                    "userId" => $submissionData->std_id,
                    "sectionId" => $submissionData->schedule->section->id,
                    "sectionNmae" => $submissionData->schedule->section->name,
                    "subject" => $submissionData->schedule->section->course->name,
                    "mess" => "Your submission code is confirmed",
                    "url" => url('view-code')."/".$submissionData->id,
                    "type" => 'submissionResult',
                ];
                event(new SectionManagerEvent($data));
            }
            return Response::json($submission,200);
        } 
        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
        
    }
    public function practiceLab(){
        $user = Auth::user();
        if ($user->permission_id == 10) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'You don\'t have permissions to access this page, Please Try Again']);
        } else {
            $langs = Languages_Type::where('lang_id','>',0)->get();
            //return $langs;
            return view('practice_labs')->with(['langs' => $langs]);
        }
    }
    public function practiceLabLang($lang){
        $user = Auth::user();
        if ($user->permission_id == 10) {
            return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'You don\'t have permissions to access this page, Please Try Again']);
        } else {
            if (filter_var($lang, FILTER_VALIDATE_INT) === false) {
                return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'Invalid Language Id, Please Try Again']);
            }
            $lang = Languages_Type::find($lang);
            return view('practice_editor')->with(['lang' => $lang]);
        }
    }
    public function runPractice(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editorData'     => ['required', 'string'],
            'langId'      => ['required', 'integer'],
        ]);
        $lang = Languages_Type::find($request->langId);
        if($lang){
            if ($user->permission_id == 10) {
                return redirect('dashboard')->with(['alert_color' => 'danger', 'message' => 'You don\'t have permissions to access this page, Please Try Again']);
            } else {
                $outputData = $this->codeCompile($request->editorData,$lang->lang_id);
                if(!$outputData[0])
                    return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
                return Response::json($outputData[1],200);
            }
        }
        return Response::json(['message'=>'Data Invalid, Please Try Again'],422);
    }

    public function codeCompile($code, $lang)
    {
        $judge0 = app(Judge0Service::class); 
        if(!empty($code) && $lang > 0){
            $response = $judge0->compileCode(base64_encode($code), $lang);
            $output = base64_decode($response['stdout']);
            return [true,$output];
        }
        return [false,''];
    }
}

