import './bootstrap';
import Swal from 'sweetalert2';
var channel = Echo.private(`schedule.user.${userId}`);
channel.listen('SectionManagerEvent', (e) => {
    if(e.data.type ==='startSession'){
        if(document.title ==='TUV - Schedules'){
            document.getElementById("schedule" + e.data.id).innerHTML = '<a href="' + e.data.url + '" class="btn btn-success">Start Now</a>';
        }
        Swal.fire({
            title: "Live Session",
            text: "Your "+e.data.subject+" section is started ",
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#9fe2a2",
            cancelButtonColor: "#d33",
            confirmButtonText: "Start Now"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = e.data.url;
            }
          });
    }
    if(e.data.type ==='stdAttendance'){
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.onmouseenter = Swal.stopTimer;
              toast.onmouseleave = Swal.resumeTimer;
            }
          });
          Toast.fire({
            icon: "success",
            title: e.data.nameMess
          });
          if(document.title ==='TUV - Instructor Session'){
            var att = document.getElementById("std" + e.data.stdId);
            att.innerHTML = 'Present';
            
            att.style.color = "#9fe2a2";
        }
    }
    if(e.data.type ==='stdSubmission'){
      const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: "success",
          title: e.data.nameMess
        });
        if(document.title ==='TUV - Instructor Session' || document.title ==='TUV - Instructor Submissions'){
          var code = document.getElementById("stdCode-" + e.data.stdId);
          var result = document.getElementById("stdresult-" + e.data.stdId);
          code.innerHTML = '<button class="btn btn-success btn-sm" onclick="getCode('+e.data.stdId+','+e.data.id+',\''+e.data.name+'\' )" type="button"><i class="fa fa-file-code-o"></i> Completed</button>';                       
          result.innerHTML = '<input class="form-control form-control text-center" onchange="result('+e.data.submissionId+')" id="submit-'+e.data.submissionId+'" value="'+e.data.result+'" type="number">'
                                    
        }
    }
    if(e.data.type ==='submissionResult'){
      if(document.title ==='TUV - Session'){
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: "success",
          title: e.data.subject,
          text: e.data.mess,
        });
       }else{
          Swal.fire({
            title: e.data.subject,
            text: e.data.mess,
            icon: "success",
            showCancelButton: true,
            confirmButtonColor: "#9fe2a2",
            cancelButtonColor: "#d33",
            confirmButtonText: "View Code"
          }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = e.data.url;
            }
          });
       }
    }
    if( e.data.type==='endSession'){
      if(document.title ==='TUV - Session'){
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          },
          didClose:(toast)=>{
            window.location.href = e.data.url;
          }
        });
        Toast.fire({
          icon: "success",
          title: e.data.subject,
          text: e.data.mess,
        });
        
      }
    }
    if( e.data.type==='viewCode'){
      if(document.title ==='TUV - Session'){
        document.getElementById("runCode").click();
      }
      
      if(document.title ==='TUV - Instructor Session'){
        $("#viewCode"+e.data.result.std_id).html('<i class="fa fa-play-circle-o"></i> View');
        $("#viewCode"+e.data.result.std_id).prop("disabled", false);
        $('#editorData').html(e.data.result.code);
        $('#outputData').html(e.data.result.output);
        $('#stdList').hide();
        $('#stdEditor').show();
      }
    }
});