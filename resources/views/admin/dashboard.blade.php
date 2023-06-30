@if (!isset(Auth::user()->username))
    <script>window.location = "/"</script>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <title>Admin - Dashboard</title>
    
</head>
<body>
<div class="justify-content-between align-items-center nav">
    <div class="d-flex justify-content-start align-items-center">
        <div style="margin-right: 1rem;">
            <img src = "{{ url('assets/image/template_image/Pangasinan_State_University_logo.png') }}" height="50rem">
        </div>

        <div>
            <h3 style="margin: 0">Pangasinan State University</h3>
        </div>
    </div>

    <div class="d-flex flex-row">
        <!-- @if (Session::has('invalid_code'))
            <div class="col-sm-4">
                <div class="alert alert-danger">
                    {{ Session::get('invalid_code') }}
                </div>   
            </div> 
        @endif -->
        @if(Session::has('invalid_code'))
            <script>
                toastr.options =
                {
                    "closeButton" : false,
                    "progressBar" : false,
                    
                }
            toastr.error("{{ session('invalid_code') }}");
            </script>
        @endif

        <div class="p-2">
            <form action="/admin/searchCert" method="post" enctype="multipart/form-data"> 
            @csrf      
            <input type="text" class="form-control" name="code" id="colFormLabel" value = "{{ old ('code') }}" placeholder="Search By Certificate ID" autocomplete="off">
        </div>
        <div class="p-2">
            <button type="submit" class="btn btn-warning text text-dark" name="save" >Search</button>
        </div>
        </form>
    </div>

    
    <div class="p-2">
        <span><a class="nav-link" href="/admin/logout" style="color: white;"><i class='bx by bx-log-out' ></i>Logout</a></span>
    </div>

</div>
        <div class="add">
            <form action="/addtraining" method="post" enctype="multipart/form-data" id = "addform">
                <div class="row">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>    
                    @endif
                    @csrf 
                    <div class="col-sm-4">
                        <label class="form-label">Seminar name:</label>
                        <input type="text" class="form-control input" name="training" id="colFormLabel" value="{{ old('training') }}" placeholder="Seminar/ Training title" autocomplete="off">
                        @error('training')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-4">
                        <label class="form-label">From:</label>
                        <input type="date" class="form-control" name="start_date" id="colFormLabel" value="{{ old('start_date') }}">
                        @error('start_date')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text text-dark mt-2">
                            <p style="font-size: 14px;">Note: Please select both equal date in from and until when the seminar is only 1 day</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Until:</label>
                        <input type="date" class="form-control" name="end_date" id="colFormLabel" value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-4 mt-2">
                        <label class="form-label">Description:</label>
                        <textarea class="form-control" name="description" id="colFormLabel" rows="4" placeholder="Description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label">Organizer e-signature:</label>
                        <input type = "file" class="form-control" name = "e-signature">
                        @error('e-signature')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text text-dark mt-2">
                            <p style="font-size: 14px;">Note: Please upload transparent e-signature background and also in contrast of background color of certificate for a better settings</p>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label">Organizer:</label>
                        <input type="text" class="form-control" name="organizer" id="colFormLabel" placeholder="Speaker" value="{{ old('organizer') }}">
                        @error('organizer')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label">Title/Position:</label>
                        <input type="text" class="form-control" name="position" id="colFormLabel" placeholder="Position" value="{{ old('position') }}">
                        @error('position')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                <div class="col-sm-4 mt-2">
                    <label class="form-label">Seminar Logo: (Optional)</label>
                    <input type="file" class="form-control" name="image" id="fileToUpload">
                    @error('image')
                        <div class="text text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                    
                <div class="col-sm-4 mt-2">
                    <label class="form-label">Template: (Optional)</label>
                        <select class="form-control" style="width:100%" id="background" name = "template">
                            <option selected="true" disabled="disabled">Choose Template</option>    
                            <option value="Template 1" > Template 1</option>
                            <option value="Template 2" > Template 2</option>
                            <option value="Template 3" > Template 3</option> 
                            <option value="Template 4" > Template 4</option>
                            <option value="Template 5" > Template 5</option>
                            <option value="Template 6" > Template 6</option>  
                            <option value="Template 7" > Template 7</option>
                            <option value="Template 8" > Template 8</option>
                            <option value="Template 9" > Template 9</option> 
                            <option value="Template 10" > Template 10</option>
                            <option value="Template 11" > Template 11</option>
                            <option value="Template 12" > Template 12</option> 
                        </select>
                        @error('template')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label mt-2">Custom Template: (Optional)</label>
                        <input type="file" class="form-control" name="img" id="fileToUpload">
                        @error('img')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text text-dark mt-2">
                            <p style="font-size: 14px;">Note: For better setting use A4 size template</p>
                        </div>
                    </div>

                    <div class="col-sm-4 mt-2">
                        <label class="form-label mt-2">Company name:</label>
                        <input type="text" class="form-control input" name="company" id="colFormLabel" value="{{ old('company') }}" placeholder="Company name" autocomplete="off">
                        @error('company')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mt-2">
                        <label class="form-label" style="color:white;">TEXT</label>
                        <button type="reset" class="btn btn-secondary" name="reset" id="reset" style="width:100%;"><i class='bx by bx-reset'></i>Reset</button>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label" style="color:white;">TEXT</label>
                        <button type="submit" class="btn btn-primary" name="save" style="width:100%;"><i class='bx by bx-plus'></i>Add </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="entry mt-5">
            <h5 style="margin-bottom: 1.5rem;">Certificate Templates</h5>
        </div>
        <div class="container p-2 overflow-hidden mx-auto">
            <div class="row mx-auto mb-3"> 
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/img2.png') }}" alt=" " width="180"> 
                    <label class="form-label fw-bold mx-auto text-center">Template 1</label>
                </div>
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/img3.png') }}" alt=" " width="180"> 
                    <label class="form-label fw-bold mx-auto text-center">Template 2</label>
                </div>
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/img5.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 3</label>
                </div>
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t1.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 4</label>
                </div>   
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t2.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 5</label>
                </div>  
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t3.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 6</label>
                </div>  
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t4.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 7</label>
                </div>      
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t5.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 8</label>
                </div>  
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t6.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 9</label>
                </div>  
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t7.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 10</label>
                </div>  
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t8.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 11</label>
                </div>  
                <div class="col-sm-2 mx-auto mt-5">
                    <img src="{{ url('assets/image/template_image/t9.png') }}" alt=" " width="180">
                    <label class="form-label fw-bold mx-auto text-center">Template 12</label>
                </div>  
            </div>
        </div>

        <div class="entry mt-5" style="margin-bottom: 100px;">
            <h5 style="margin-bottom: 1.5rem;">Seminars/Trainings</h5>
            <div class="row">
                @if(Session::has('update'))
                    <div class="alert alert-success">
                        <p>{{ Session::get('update') }}</p>
                    </div>
                @endif
                <div class="row overflow-hidden">
                <div class="col-sm-12 mx-auto overflow-hidden">
                    <table id="tblSeminars" class="table table-striped table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <th hidden>ID</th>
                            <th>Seminar name</th>
                            <th>Seminar date</th>
                            <th>Seminar template</th>
                            <th>Seminar Logo</th>
                            <th>Organizer Name</th>
                            <th>Organizer's Signature</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                        @if ($list != NULL)
                            @foreach ($list as $item)
                                <tr>
                                    <td hidden>{{ $item->training_id }}</td>
                                    <td>
                                        @if ($item->status == "ACTIVE")
                                        <font class="fw-bold text-dark">{{$item->training}}</font>
                                        @else
                                        <a href="/view/{{$item->training_id}}" class="fw-bold text-dark" style="text-decoration:none;">{{$item->training}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->from_start_date != $item->until_end_date)
                                            {{ $item->from_start_date }} - {{ $item->until_end_date }}
                                        @else
                                            {{ $item->from_start_date  }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->img != NULL)
                                            <img src ="{{ url('assets/image/template/'.$item->img.'') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 1")
                                            <img src ="{{ url('assets/image/template_image/img2.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 2")
                                            <img src ="{{ url('assets/image/template_image/img3.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 3")
                                            <img src ="{{ url('assets/image/template_image/img5.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 4")
                                            <img src ="{{ url('assets/image/template_image/t1.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 5")
                                            <img src ="{{ url('assets/image/template_image/t2.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 6")
                                            <img src ="{{ url('assets/image/template_image/t3.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 7")
                                            <img src ="{{ url('assets/image/template_image/t4.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 8")
                                            <img src ="{{ url('assets/image/template_image/t5.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 9")
                                            <img src ="{{ url('assets/image/template_image/t6.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 10")
                                            <img src ="{{ url('assets/image/template_image/t7.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 11")
                                            <img src ="{{ url('assets/image/template_image/t8.png') }}" class="img-responsive" height="200" width="300">
                                        @elseif($item->logo == "Template 12")
                                            <img src ="{{ url('assets/image/template_image/t9.png') }}" class="img-responsive" height="200" width="300">
                                        @endif 
                                    </td>
                                    <td>
                                        @if ($item->image == null)
                                            <font class="text-danger">No Logo Provided</font>
                                        @else
                                            <img src ="{{ url('assets/image/logo/'.$item->image) }}" class="img-responsive" height="200" width="200">
                                        @endif    
                                    </td>
                                    <td>{{ $item->organizer }}</td>
                                    <td><img src="{{ url('assets/image/e-signature/'.$item->signature) }}" class="img-responsive" height="100" width="130"></td>
                                    <td>
                                        @if ($item->status == "ACTIVE")                    
                                            <font class="text text-success fw-bolder">{{ $item->status }}</font>
                                        @else
                                            <font class="text text-danger fw-bolder">{{ $item->status }}</font>
                                        @endif
                                    </td>
                                    <td> 
                                        <div class="d-flex justify-content-end mt-3">
                                            <a href="/admin/edit/{{$item->training_id}}" class="btn btn-warning"><i class='bx by bxs-edit-alt' ></i>EDIT</a>
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this entry?');" ><a href="/admin/delete/{{$item->training_id}}" class="text-light" style="text-decoration:none;"><i class='bx by bxs-trash-alt' ></i>DELETE</a></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('#tblSeminars').DataTable({
            select: true,
            scrollX: true,
            order : [[0, "desc"]]
        });
    });
</script>
</body>
</html>