@if (!isset(Auth::user()->username))
    <script>window.location = "/"</script>
@endif
<?php
    $i = str_replace('["',"",$id);
    $d = str_replace('"]',"",$i);
?>
<!doctype html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Admin Dashboard - Generate</title>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");
        body {
            overflow-x:hidden; /* Hide scrollbars */
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
            <a class="nav-link" href="/admin/logout" style="color: white;"><i class='bx by bx-log-out' ></i></i>Logout</a>
        </div>
    </div>
    @if ($select != null)
        @foreach ($select as $item)
            @if ($item->status == "INACTIVE")
            <div class="add">
            <h3 class="mb-3"><a href="{{ url('admin/dashboard') }}"><i class='bx bx-left-arrow-alt'></i></a>{{$d}}</h3>

            <form action="{{ route('generate', $d) }}" method="post" enctype="multipart/form-data"> 
            @csrf
            <input type="hidden" value = "{{ $train }}" name = "training_id">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-6">
                        @if (Session::has('success'))
                            <div class="alert alert-success">
                                <li>{{ Session::get('success') }}</li>
                            </div>    
                        @endif
                        @if (Session::has('internet_error'))
                            <div class="alert alert-danger">
                                <li>{{ Session:get('internet_error') }}</li>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" id="colFormLabel" placeholder="Name" value="{{ old('name') }}">
                    @error('name')
                        <div class="text text-danger mt-2">{{ $message }}</div>
                    @enderror
                    @if (Session::has('duplicate'))
                        <div class="text text-danger mt-2">{{ Session::get('duplicate') }}</div>
                    @endif
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Email address to send (Optional):</label>
                    <input type="email" class="form-control" name="email" id="colFormLabel" placeholder="Email" value="{{ old('email') }}">
                    @error('email')
                        <div class="text text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-2">
                
                <div class="col-sm-4">
                    <label class="form-label" style="display: none;">Hidden</label>
                    <button type="submit" class="btn btn-primary" style="margin-top:30px; width:100%;" name="save" >Generate </button>
                </div>
            </div>
        </form>
    </div>
            @endif
        @endforeach
    @endif
<hr size="1">
    <div class="entry mt-5">
        <h5 style="margin-bottom: 1.5rem;">Certificates</h5>
        <div class="row">
            <div class="col-sm-12">
                @if(Session::has('update'))
                    <div class="alert alert-success">
                        <p>{{ Session::get('update') }}</p>
                    </div>
                @endif
                
                <table id="tblUser" class="table table-striped table-bordered dt-responsive" style="width:100%">
                    <thead>
                        <th>QR</th>
                        <th>Certificate ID</th>
                        <th>Participant Name</th>
                        <th>Participant Email</th>
                        <th>Created At</th>
                        <th>Email Sent At</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @if ($fetch != NULL)
                        @foreach ($fetch as $item)
                            <tr>
                                <td><a href = "/admin/view/certificate/{{ $item->id }} " target="_blank">{{$qr = QrCode::size(100)->generate('item->certificate_id');}}</a> </td>
                                <td>{{$item->certificate_id}}</td>
                                <td>{{$item->name}}</td>
                                <td>
                                    @if ($item->email != null)
                                        {{$item->email}}
                                    @else
                                        <font class = "text text-danger">No email provided</font>
                                    @endif
                                </td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->email_sent_date_time}}</td>
                                <td><a class="retrieve btn btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#resendModal" data-id = "{{ $item->id }}">SEND</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<div class="modal fade" id="resendModal" tabindex="-1" aria-labelledby="resendModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="{{ route('resend', 'id') }}" id="resend">
        @csrf
        <input id="id" name="id" hidden>
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Resend certificate through email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @if (Session::has('resend_success'))
                <div class="alert alert-success">
                    <p>Resend certificate success, please check your inbox!</p>
                </div>      
            @endif
            @if (Session::has('resend_internet_error'))
                <div class="alert alert-danger">
                    <p>Unable to send certificate, please check your internet connection</p>
                </div>    
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <p>Something went wrong</p>
                </div>    
            @endif
            <label class = "form-label">Email: </label>
            <input type = "email" class = "form-control" name = "resend_email"  id = "resend_email" placeholder="Email"/>
            @error('resend_email')
                <div class="text text-danger mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" name = "sendEmail" id = "sendEmail">Send</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('#tblUser').DataTable({
            select: true,
            scrollX: true,
        });
    });

    $(document).on('click', '.retrieve' , function(){
        let id = $(this).attr('data-id');
        let email = $(this).attr('');
        $('#id').val(id);
        $('#resendModal').modal('show');
    });
</script>

</body>

</html>
