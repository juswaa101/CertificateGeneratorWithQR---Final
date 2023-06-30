<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MainController extends Controller
{
    function dashboard(){

        $data = array(
            'list'=>DB::table('training')->get(),
        );
        return view('admin.dashboard', $data);
    }

    function addtraining(Request $request){

        $validator = Validator::make($request->all(), [
            'training' => 'required|unique:training|max:255',
            'image' => 'mimes:png|image',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'img' => 'mimes:png|image', 
            'e-signature' => 'required|mimes:png|image',
            'description' => 'required',
            'organizer' => 'required',
            'position' => 'required',
        ]);
        $description = $request->get('description');
        $organizer = $request->get('organizer');
        $position = $request->get('position');

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            if($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('assets/image/logo/', $filename);   
                if($request->hasFile('img'))
                {   
                    $file1 = $request->file('img');
                    $extension1 = $file1->getClientOriginalExtension();
                    $filename1 = time() . '.' . $extension1;
                    $file1->move('assets/image/template/', $filename1); 
                    

                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename);  
                        $query = DB::table('training')->insert([
                            'training'=> $request->input('training'),
                            'image' => $filename,
                            'logo' => $request->get('template'),
                            'img' => $filename1,
                            'company' => $request->input('company'),
                            'from_start_date' => $request->input('start_date'),
                            'until_end_date' => $request->input('end_date'), 
                            'signature' => $filename,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => 'ACTIVE'
                        ]);
                    }
                }
                else{
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename);  
                        $query = DB::table('training')->insert([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'company' => $request->input('company'),
                            'from_start_date' => $request->get('start_date'),
                            'until_end_date' => $request->get('end_date'), 
                            'logo' => $request->get('template'),
                            'signature' => $filename,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => 'ACTIVE'
                        ]);
                    }
                }
                if($query){
                    return back()->with('success', 'Seminar set successful!');
                } else
                    return back()->with('fail', 'Seminar set unsuccessful!');
            }
            else{
                if($request->hasFile('img'))
                {
                    $file1 = $request->file('img');
                    $extension1 = $file1->getClientOriginalExtension();
                    $filename1 = time() . '.' . $extension1;
                    $file1->move('assets/image/template/', $filename1); 
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename);  
                        $query = DB::table('training')->insert([
                            'training'=> $request->input('training'),
                            'img' => $filename1,
                            'company' => $request->input('company'),
                            'from_start_date' => $request->input('start_date'),
                            'until_end_date' => $request->input('end_date'), 
                            'signature' => $filename,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => 'ACTIVE'
                        ]);
                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }
                else
                {
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename);  
                        $query = DB::table('training')->insert([
                            'training'=> $request->input('training'),
                            'logo' => $request->get('template'),
                            'from_start_date' => $request->input('start_date'),
                            'until_end_date' => $request->input('end_date'), 
                            'company' => $request->input('company'),
                            'signature' => $filename,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => 'ACTIVE'
                        ]);
                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }
            }
        }
    }

    function deletetraining($id){
        $certificate = Certificate::where('training_id', $id);
        $query = DB::delete('DELETE FROM training WHERE training_id = ?', [$id]);
       
        if($query){
            $certificate->delete();
            return redirect()->back()->with('success', 'Data deleted');
        } else
            return redirect()->back()->with('fail', 'Data not deleted');

    }

    function edittraining($id){
        $edit = DB::select('select * from training where training_id = ?', [$id]);
        return view('admin.edit', ['edit' => $edit]);
    }

    function viewtraining($id){
        $id = request()->segment(2);
        $fetch = DB::select('select * from certificates where training_id = ?', [$id]);
        $select = DB::select('select * from training where training_id = ?', [$id]);
        $data = array(
            'id'=>DB::table('training')->where('training_id', $id)->pluck('training'),
            'list'=>DB::table('training')->where('training_id', $id)->first()
        );
        return view('admin.generate', $data)->with('fetch', $fetch)->with('train', request()->segment(2))->with('select', $select);
    }

    function updatetraining(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'training' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after_or_equal:start_date',
            'description' => 'required',
            'organizer' => 'required',
            'position' => 'required',
        ]);
        $training = $request->get('training');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $template = $request->get('template');
        $description = $request->get('description');
        $organizer = $request->get('organizer');
        $position = $request->get('position');
        $status = $request->get('status');
        
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            if($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('assets/image/logo/', $filename);   

                if($request->hasFile('img')){
                    $file1 = $request->file('image');
                    $extension1 = $file1->getClientOriginalExtension();
                    $filename1 = time() . '.' . $extension1;
                    $file1->move('assets/image/template/', $filename1); 

                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename2 = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename2);  
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'img' => $filename1,
                            'logo' =>  "",
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'signature' => $filename2,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                    else{
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'img' => $filename1,
                            'logo' =>  "",
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }

                else if ($template != NULL){
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename2 = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename2);  
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'logo' =>  $template,
                            'img' => "",
                            'signature' => $filename2,
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                    else
                    {
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'logo' =>  $template,
                            'img' => "",
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);
    
                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }
                else if (!$request->hasFile('img') && $template == NULL ) {
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename2 = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename2);  
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'signature' => $filename2,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);
    
                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                    else
                    {
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'image' => $filename,
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }
    
               
            }

            else{
                if($request->hasFile('img')){
                    $file1 = $request->file('img');
                    $extension1 = $file1->getClientOriginalExtension();
                    $filename1 = time() . '.' . $extension1;
                    $file1->move('assets/image/template/', $filename1); 
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename2 = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename2);  
                        $query = DB::table('training')->where('training_id', $id)->update([
                            'training' => $training,
                            'img' => $filename1,
                            'logo' => $template,
                            'from_start_date' => $start_date,
                            'until_end_date' => $end_date,
                            'signature' => $filename2,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                    else{
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'img' => $filename1,
                            'logo' =>  "",
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }

                else if ($template != NULL) {
                    if($request->hasFile('e-signature'))
                    {
                        $file = $request->file('e-signature');
                        $extension = $file->getClientOriginalExtension();
                        $filename2 = time() . '.' . $extension;
                        $file->move('assets/image/e-signature/', $filename2);  
                        $query = DB::table('training')->where('training_id', $id)->update([
                            'training' => $training,
                            'logo' => $template,
                            'from_start_date' => $start_date,
                            'until_end_date' => $end_date,
                            'signature' => $filename2,
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status
                        ]);

                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                    else
                    {
                        $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                            'training'=> $request-> input('training'),
                            'logo' =>  $template,
                            'img' => "",
                            'from_start_date'=> $start_date,
                            'until_end_date' => $end_date, 
                            'description' => $description,
                            'organizer' => $organizer,
                            'position' => $position,
                            'status' => $status,
                        ]);
    
                        if($query){
                            return back()->with('success', 'Seminar set successful!');
                        } else
                            return back()->with('fail', 'Seminar set unsuccessful!');
                    }
                }

                else if (!$request->hasFile('img') && $template == NULL ) {
                    $query = DB::table('training')->where('training_id', $request->segment(2))->update([
                        'training'=> $request-> input('training'),
                        'from_start_date'=> $start_date,
                        'until_end_date' => $end_date, 
                        'description' => $description,
                        'organizer' => $organizer,
                        'position' => $position,
                        'status' => $status,
                    ]);

                    if($query){
                        return back()->with('success', 'Seminar set successful!');
                    } else
                        return back()->with('fail', 'Seminar set unsuccessful!');
                }


            }
            if($request->hasFile('e-signature'))
            {
                $file = $request->file('e-signature');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('assets/image/e-signature/', $filename);  
                DB::table('training')->where('training_id', $id)->update([
                    'training' => $training,
                    'logo' => $template,
                    'from_start_date' => $start_date,
                    'until_end_date' => $end_date,
                    'signature' => $filename,
                    'description' => $description,
                    'organizer' => $organizer,
                    'position' => $position,
                    'status' => $status
                ]);
            }
            else
            {
                DB::table('training')->where('training_id', $id)->update([
                    'training' => $training,
                    'logo' => $template,
                    'from_start_date' => $start_date,
                    'until_end_date' => $end_date,
                    'description' => $description,
                    'organizer' => $organizer,
                    'position' => $position,
                    'status' => $status
                ]);
            }
            // DB::update('update training set training = "'.$training.'", logo = "'.$template.'", from_start_date = "'.$start_date.'", until_end_date = "'.$end_date.'", description = "'.$description.'", organizer = "'.$organizer.'", position = "'.$position'", status = "'.$status.'" where training_id = ?' ,[$id]);
            return back()->with('success', 'Seminar set successful!');
        } 
    }

    function searchCert(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'min:32|max:32|required',
        ]);
        $id = $request->input('code');
        if($validator->fails())
        {
            return redirect()->back()->with('invalid_code', 'Please enter a valid certificate id');
        }
        else
        {
            $query = DB::select('select * from certificates INNER JOIN training ON certificates.training_id = training.training_id WHERE certificates.certificate_id = "'.$id.'";');
            return view('admin.search')->with('fetch', $query);
        }
    }

    function searchUserCert(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'min:32|max:32|required',
        ]);
        $id = $request->input('code');
        if($validator->fails())
        {
            return redirect()->back()->with('invalid_code', 'Please enter a valid certificate id');
        }
        else
        {
            $query = DB::select('select * from certificates INNER JOIN training ON certificates.training_id = training.training_id WHERE certificates.certificate_id = "'.$id.'";');
            return view("search")->with('fetch', $query);
        }
    }

    function userView(){
        return view('index');
    }
}
