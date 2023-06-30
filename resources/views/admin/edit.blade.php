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
    <title>Edit Training</title>
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

    <div class="add">
        <h5 style="margin-bottom: 1.5rem;"><a href="{{ url('admin/dashboard') }}"><i class='bx bx-left-arrow-alt'></i></a>Edit Seminar/Training Details</h5>
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('fail'))
            <div class="alert alert-danger">
                {{ Session::get('fail') }}
            </div>
        @endif
        @foreach ($edit as $item)
            <form action="{{ route('update.training', $item->training_id) }}" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-4"> 
                        @csrf
                        <label for="training" class="form-label">Training/Seminar title</label>
                        <input type="text" class="form-control" value = "{{ $item->training }}" name="training" id="colFormLabel">
                        @error('training')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">From:</label>
                        <input type="date" class="form-control" name="start_date" id="colFormLabel" value="{{ $item->from_start_date }}">
                        @error('start_date')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text text-dark mt-2">
                            <p style="font-size: 14px;">Note: Please select both equal date in from and until when the seminar is only 1 day</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Until:</label>
                        <input type="date" class="form-control" name="end_date" id="colFormLabel" value="{{ $item->until_end_date }}">
                        @error('end_date')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-4">
                        <label class = "form-label d-flex">Status: &nbsp;
                            @if ($item->status == "ACTIVE")                    
                                <font class="text text-success fw-bolder">{{ $item->status }}</font>
                            @else
                                <font class="text text-danger fw-bolder">{{ $item->status }}</font>
                            @endif
                        </label>
                        <select class = "form-control" style="width:100%" name = "status">
                            @if ($item->status == "ACTIVE")
                                <option value = "{{ $item->status }}">{{ $item->status }}</option>
                                <option value = "INACTIVE">INACTIVE</option>
                            @else
                                <option value = "{{ $item->status }}">{{ $item->status }}</option>
                                <option value = "ACTIVE">ACTIVE</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-4">
                       
                            <label class="form-label">Custom Template:</label>
                            <input type="file" name = "img" class="form-control"/>
                            <div class="text text-dark mt-2">
                            <p style="font-size: 14px;">Note: For better setting use A4 size template</p>
                            </div>
                        
                    </div>
                    <div class="col-sm-4">

                        <label class="form-label">Default Template:</label>
                            <select class="form-control" style="width:100%" id="background" name = "template">
                                <option value="{{ $item->logo }}">{{ $item->logo }}</option>
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
                        
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label mt-2">Description:</label>
                        <textarea class="form-control" name="description" id="colFormLabel" rows="4" placeholder="Description">{{ $item->description }}</textarea>
                        @error('description')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label mt-2">Organizer e-signature:</label>
                        <input type = "file" class="form-control" name = "e-signature">
                        @error('e-signature')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <div class="text text-dark mt-2">
                            <p style="font-size: 14px;">Note: Please upload transparent e-signature background and also in contrast of background color of certificate for a better settings</p>
                        </div>
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label mt-2">Organizer:</label>
                        <input type="text" class="form-control" name="organizer" id="colFormLabel" placeholder="Speaker" value="{{ $item->organizer }}">
                        @error('organizer')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label">Title/Position:</label>
                        <input type="text" class="form-control" name="position" id="colFormLabel" placeholder="Position" value="{{ $item->position }}">
                        @error('position')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-4 mt-2">
                        <label class="form-label">Seminar Logo:</label>
                        <input type="file" class="form-control" name="image" id="fileToUpload">
                        @error('image')
                            <div class="text text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mt-3">
                        <button type="submit" class="btn btn-warning mt-4" name="save" style="width: 100%;"><i class='bx by bxs-edit-alt' ></i>Update</button>
                    </div>
                    <div class="col-sm-4  mt-3">
                        <a href="{{ url('admin/dashboard') }}" class="btn btn-danger mt-4" style="width: 100%;"><i class='bx by bx-block' ></i>Cancel</a>
                    </div>
                </div>
            </form>
        @endforeach
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
</body>
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
</html>
