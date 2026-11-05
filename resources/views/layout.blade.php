<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">



        <title>iLab - @yield('title')</title>
        <style>
            .custom-button{
                min-height: 80px;
                line-height: 80px;
                text-align: center;
                background-color:#27aae1;
                color:#fff
            }
            .custom-button:hover {
                background-color: #111dc7;
                color:#fff
            }
            .custom-login-button{
                min-height: 40px;
                line-height: 40px;
                margin-top: 80px;
                text-align: center;
                background-color:#111dc7;
                color:#fff
            }

            .custom-login-small-button{
                text-align: center;
                background-color:#27aae1;
                color:#fff
            }

            .custom-login-button:hover {
                background-color: #111dc7;
                color:#fff
            }
        </style>
        @yield('head')
    </head>
    <body class="container-fluid justify-content-center d-flex align-items-center">
        <div class="d-flex flex-column col-12 min-vh-100 justify-content-center align-items-center">
            <div class="row col-12 justify-content-between align-self-center">
                <nav class="navbar navbar-expand-lg  fixed-top" data-bs-theme="dark" style="background-color: #000000;">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/">
                            <img src="{{ URL::asset('/images/ilab_logo_white.png')}}" alt="Logo"  height="35" class="d-inline-block align-text-top">
                            
                        </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active fw-bolder" aria-current="page" href="/" style="color:#ffffff"><i class="fa fa-home" style="color:#27aae1"></i> HOME</a>
                            </li>


                            <li class="nav-item">
    <a class="nav-link fw-bolder" href="{{ route('about_us') }}" style="color:#ffffff">
        <i class="fa fa-exclamation-circle" style="color:#27aae1"></i> ABOUT Us
    </a>
</li>


                            <li class="nav-item">
    <a class="nav-link fw-bolder" href="{{ route('contact_us') }}" style="color:#ffffff">
        <i class="fa fa-comments" style="color:#27aae1"></i> CONTACT US
    </a>
</li>


                        </ul>
                        @if(auth()->user())
                            <form class="d-flex" role="search">
                                <a class="text" style="color:#ffffff" data-bs-toggle="modal" data-bs-target="#Logout" aria-current="page" href="#">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                                </a>
                            </form>
                        @endif
                    </div>
                    </div>
                </nav>
            </div>
            @yield('content')
            <div class="row mt-auto col-12 ">
                <nav class="navbar navbar-expand-lg justify-content-center fixed-bottom" data-bs-theme="dark" style="background-color: #000000;">

<a href="https://www.instagram.com/ilab.sa/" target="_blank" rel="noopener noreferrer">
    <i class="fa-brands fa-instagram" style="color:#ffffff; font-size: 24px;"></i>
</a>


<a class="h3 m-2 text-white" href="https://x.com/iLabteam" target="_blank" rel="noopener noreferrer">
    <i class="fa-brands fa-x-twitter" style="color:#ffffff" aria-hidden="true"></i>
</a>




                </nav>
            </div>
        </div>
        @if(auth()->user())
            <div class="modal fade" id="Logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="LogoutLabel" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="LogoutLabel">Logout</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        <div class="modal-body">
                            <div class="alert alert-danger text-center mt-4" role="alert">
                                Are you sure do you want to Logout?
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <a href="{{ url('logout') }}" class="btn btn-danger">Yes</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @yield('script')
    </body>
</html>
