@if (isset(Auth::user()->username))
    <script>window.location = "/admin/dashboard"</script>
@endif
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('../css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/6d6b82be0b.js" crossorigin="anonymous"></script>
    <script type='text/javascript' src=''></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>User - Search</title>
    <style>
        body{
            height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<div class="d-flex flex-row flex-wrap justify-content-between align-items-center nav">
    <div class="d-flex justify-content-start align-items-center">
        <div style="margin-right: 1rem;">
            <img src = "{{ url('assets/image/template_image/Pangasinan_State_University_logo.png') }}" height="50rem">
        </div>

        <div>
            <h3 style="margin: 0">Pangasinan State University</h3>
        </div>
    </div>

    <div>
        <a class="nav-link" href="/admin/login" style="color: white;"><i class='bx by bx-log-in' ></i>Login</a>
        <a class="nav-link" href="{{ url('assets/User-Manual.pdf') }}" target="_blank" style="color: white;"><i class='bx by bi bx-info-circle' ></i>How to use the system?</a>
    </div>
</div>

<div class="d-flex justify-content-center align-items-center">
    <form action="user/searchCert" method="post" enctype="multipart/form-data"> 
        @csrf
        <div class="d-flex flex-column justify-content-start" style="margin-top: 10rem;">
            <div>
                <h2 class="mb-3">Search Certificate</h2>
                <div class="row">
                @if (Session::has('invalid_code'))
                    <div>
                        <div class="alert alert-danger">
                            <li>{{ Session::get('invalid_code') }}</li>
                        </div>   
                    </div> 
                @endif
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center">
                <div style="margin-right: 1rem">
                    <input type="text" autocomplete="off"  class="form-control" name="code" id="colFormLabel" value = "{{ old ('code') }}" placeholder="Search By Certificate ID" style="width: 25rem;">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" name="save" >Search</button>
                </div>
            </div>

            <div class="mt-1">
                <span class="note">
                    Note: Certificate ID must have a 32 characters.
                </span>
            </div>
        </div>
    </form>
</div>

<div class="footer-container">
    <div>
        <span>
            <h5 style="color: #FFFFFF; margin-bottom: 1rem;">Designers and Developers</h5>
        </span>

        <div class="dev-container">
            <div>
                <span class="footer-text">
                    Aisle Lush S. Valdez
                </span>
                <br/>
                <span class="footer-text">
                    Angel Joy B. Manipon
                </span>
                <br/>
                <span class="footer-text">
                    Jan Patrick D.C Urbano
                </span>
                <br/>
             
                <span class="footer-text">
                    Marvin D. Bautista
                </span>
                <br/>
                <span class="footer-text">
                    Norene Ann B. Rabara
                </span>
            </div>

            <div>
                <span class="footer-text">
                    Joshua Maurice C. Yaacoub
                </span>
                <br/>
                <span class="footer-text">
                    Jackelyn N. Corpuz
                </span>
                <br/>
                <span class="footer-text">
                    Edilyn R. De Guzman
                </span>
                <br/>
                <span class="footer-text">
                    John Paul C. Pimentel
                </span>
            </div>
        </div>
    </div>

    <div>
        <span>
            <h5 style="color: #FFFFFF; margin-bottom: 1rem;">Contact us</h5>
        </span>

        <span class="footer-text">
            <i class='bx by bxs-phone'></i>(075) 632 2559
        </span>
        <br/>
        <span class="footer-text">
        <i class='bx by bxs-envelope'></i>psu_urd@gmail.com
        </span>
        <br/>
        <span class="footer-text">
        <i class='bx by bx-globe'></i>http://www.psu.edu.ph/
        </span>
        <br/>
        <span class="footer-text">
        <i class='bx by bxs-location-plus'></i>San Vicente 2428 Urdaneta, Philippines
        </span>
    </div>

    <div>
        <span>
            <h5 style="color: #FFFFFF; margin-bottom: 1rem;">Legal</h5>
        </span>

        <div class="mb-2">
            <img src = "{{ url('assets/image/template_image/flogo.png') }}" style="height: 5.5rem">
        </div>

        <span class="footer-text">
            All rights reserved
        </span>
        <br/>
        <span class="footer-text">
            {{ Date("Y") }}<sup>©</sup> Pangasinan State University
        </span>
    </div>


</div>
</body>
</html>