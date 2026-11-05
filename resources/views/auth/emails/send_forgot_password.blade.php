<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>forgot | {{ config('app.name') }}</title>
    <meta name="description" content="Reset Password Email Template.">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #ffffff;" leftmargin="0" class="text-lowercase">
<!--100% body table-->
<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#ffffff"
       style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
    <tr>
        <td>
            <table style="background-color: #111dc7; max-width:670px;  margin:0 auto;" width="100%" border="0"
                   align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="height:80px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="height:20px;">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                               style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                            <tr>
                                <td style="height:40px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding:0 35px;">
                                    <h1 style="color:#27aae1; font-weight:600; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">
                                        <strong>{{ config('app.name') }}</strong>
                                    </h1>
                                    <h3 style="color:#27aae1; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">
                                        Reset Password
                                    </h3>
                                    <span style="display:inline-block; vertical-align:middle; margin:20px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                    <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                        You recently requested to reset the password for your account. Click the button below to proceed.
                                    </p>
                                    <a href="{{route('reset.password',$token)}}"
                                       style="background:#27aae1;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:lowercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">
                                       Reset Password
                                    </a>
                                    <br>
                                    <span style="display:inline-block; vertical-align:middle; margin:20px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                    <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                        If you did not make this request then please ignore this email.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:40px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                <tr>
                    <td style="height:20px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <p style="font-size:14px; color:#fff; line-height:18px; margin:0 0 0;">&copy; <strong>{{ config('app.name') }}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td style="height:80px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!--/100% body table-->
</body>

</html>