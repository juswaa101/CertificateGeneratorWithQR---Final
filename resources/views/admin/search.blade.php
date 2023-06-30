@if (!isset(Auth::user()->username))
    <script>window.location = "/"</script>
@endif
<!doctype html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Admin - Search</title>
    <link rel="stylesheet" href="{{ url('../css/style.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/6d6b82be0b.js" crossorigin="anonymous"></script>
    <script type='text/javascript' src=''></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <style>
        body{
            overflow-x: hidden;
        }
    </style>
</head>
<body>
<div class="justify-content-between align-items-center shadow nav">
    <div class="d-flex justify-content-start align-items-center">
        <div style="margin-right: 1rem;">
            <img src = "{{ url('assets/image/template_image/Pangasinan_State_University_logo.png') }}" height="50rem">
        </div>

        <div>
            <h3 style="margin: 0">Pangasinan State University</h3>
        </div>
    </div>


    
    <div class="p-2">
        <span><a class="nav-link" href="/admin/logout" style="color: white;">Logout</a></span>
    </div>

</div>

    <div class="search">
    <h3 class="mt-3 ml-5"><a href="{{ url('admin/dashboard') }}"><i class='bx bx-left-arrow-alt'></i></a>Search</h3>
        
        
    
    </div>


    <center>
        @if ($fetch == null)
            <div class="col-sm-6 mx-auto mt-5" style="margin-bottom: 8px;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text text-dark">NOT FOUND!</h3>
                    </div>
                    <div class="card-body">
                        <img src="{{ url('assets/image/not_found.png') }}" height="200" width="200">
                        <p>The certificate id you entered was not found!</p>
                    </div>
                </div>
            </div>
        @endif


        <div class="row">
            @if($fetch != null)
                @foreach ($fetch as $item)
                    <div class="col-sm-6 mx-auto mt-2">
                        <div class="card">
                            <div class="card-header shadow-sm">
                                <h3 class="text text-dark">CERTIFICATE FOUND!</h3>
                            </div>
                            <div class="card-body">
                                @if ($item->status == "ACTIVE")
                                <a href = "/view/certificate/{{ $item->id }}" target="_blank">{{QrCode::size(150)->generate($item->certificate_id);}}</a>
                                @endif
                                <p class="mt-2">Certificate ID: {{ $item->certificate_id }}</p>
                                <p>Seminar: {{ $item->training }}</p>
                                <p>Name: {{ $item->name }}</p>
                                <p>Organizer: {{ $item->organizer }}</p>
                            </div>

                                <div class="card-footer">
                                    <p>Click here to download your certificate</p>
                                    <a class="btn btn-success" href="/admin/view/certificate/{{ $item->id }}">DOWNLOAD</a>
                                </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </center>
    <br><br>
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'></script>
<div class="footer-container2">
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
  <!-- Copyright -->

</footer>
</body>

</html>
