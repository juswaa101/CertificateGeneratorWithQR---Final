<?php

namespace App\Http\Controllers;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\MailCertificate;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PdfController extends Controller
{

    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf('L', 'mm', array(100, 100));
    }

    public function resend(Request $request)
    {
        $id = $request->id;
        $certificate = DB::select('select * from certificates INNER JOIN training ON certificates.training_id = training.training_id where certificates.id = ?', [$id]);
        $validate = Validator::make($request->all(), [
            'resend_email' => 'required|email'
        ]);
        $timestamp = date("Y-m-d H:i:s");
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        } else {
            DB::update('update certificates set email = "' . $request->get('resend_email') . '", email_sent_date_time = "' . $timestamp . '"  where id = ?', [$id]);
            if ($certificate != null) {
                foreach ($certificate as $item) {
                    $mail_data = [
                        'fromEmail' => "developers.laravel@example.com",
                        'fromName' => "psu.developers.it-3c",
                        'subject' => "Resend Certificate",
                        'body' => 'Here is your generated certificate, thanks for participating in the event!',
                        'name' => $item->name,
                        'day' => $item->from_start_date . " - " . $item->until_end_date,
                        'id' => $item->certificate_id,
                        'training' => $item->training,
                        'url' => url('/') . '/download/certificate/' . $item->id,
                    ];
                    if ($this->isOnline()) {
                        Mail::to($request->get('resend_email'))->send(new MailCertificate($mail_data));
                        return redirect()->back()->with('resend_success', 'Certificate Generated and it was send to the email');
                    } else {
                        return redirect()->back()->with('resend_internet_error', 'Unable to send certificate, please check your internet connection');
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Something went wrong');
            }
        }
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        $name = $request->get('name');
        $timestamp = date("Y-m-d H:i:s");

        // validation
        $query = DB::select('select * from certificates where name = "' . $name . '" AND training_id = "' . $request->training_id . '"');
        if (count($query) > 0) {
            return redirect()->back()->with('duplicate', 'Only one person can generate this certificate only!')->withInput();
        }

        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        } else {
            if ($request->get('email') != null) {
                $certifyId = DB::table('certificates')->insertGetId([
                    'training_id' => $request->get('training_id'),
                    'certificate_id' => Str::random(32),
                    'name' => $name,
                    'email' => $request->get('email'),
                    'email_sent_date_time' => $timestamp,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp
                ]);
                $certid =  DB::table('certificates')->where('training_id', $request->get('training_id'))->where('name', $name)->pluck('certificate_id');
                $cert = str_replace('["', '', $certid);
                $id = str_replace('"]', '', $cert);

                $training = DB::table('training')->where('training_id', $request->get('training_id'))->pluck('training');
                $train = str_replace('["', '', $training);
                $tr = str_replace('"]', '', $train);

                $mail_data = [
                    'fromEmail' => "developers.laravel@example.com",
                    'fromName' => "psu.developers.it-3c",
                    'subject' => "Generated Certificate",
                    'body' => 'Here is your generated certificate, thanks for participating in the event!',
                    'name' => $name,
                    'id' => $id,
                    'training' => $tr,
                    'url' =>  url('/') . '/download/certificate/' . $certifyId,
                ];
                if ($this->isOnline()) {
                    Mail::to($request->get('email'))->send(new MailCertificate($mail_data));
                    return redirect()->back()->with('success', 'Certificate Generated and it was send to the email');
                } else {
                    return redirect()->back()->with('internet_error', 'Unable to send certificate, please check your internet connection');
                }
            } else {
                $certifyId = DB::table('certificates')->insertGetId([
                    'training_id' => $request->get('training_id'),
                    'certificate_id' => Str::random(32),
                    'name' => $name,
                    'email' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                return redirect()->back()->with('success', 'Certificate Generated');
            }
        }
    }

    public function isOnline($site = "https://youtube.com")
    {
        if (@fopen($site, "r")) {
            return true;
        } else {
            return false;
        }
    }

    public function view($id)
    {
        $query = DB::select('select * from certificates INNER JOIN training ON certificates.training_id = training.training_id WHERE certificates.id = "' . $id . '";');
        if ($query != null) {
            foreach ($query as $fetch) {
                if (isset($fetch->id)) {
                    if ($fetch->img != NULL) {
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $logo = 'assets/image/template/' . $fetch->img . '';
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['500', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(15);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(150);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 355, 300, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 120, 280, 0, 60, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');

                        $this->fpdf->SetY(-50);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 1") {
                        $logo = 'assets/image/template_image/img2.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(15);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(150);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 290, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 100, 280, 0, 60, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);

                        $this->fpdf->SetY(-60);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 2") {
                        $logo = 'assets/image/template_image/img3.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(40);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(110);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 290, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 105, 275, 0, 50, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);

                        $this->fpdf->SetY(-60);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 3") {
                        $logo = 'assets/image/template_image/img5.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(40);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(145);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 290, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 100, 275, 0, 60, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);

                        $this->fpdf->SetY(-60);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 4") {
                        $logo = 'assets/image/template_image/t1.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(35);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(115);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 245, 260, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 243, 300, 0, 50, 'PNG');

                        $this->fpdf->SetY(-25);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 20);

                        $this->fpdf->SetY(-90);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 5") {
                        $logo = 'assets/image/template_image/t2.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(50);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(100);
                        $this->fpdf->SetTextColor(245, 208, 25);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 290, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 105, 275, 0, 50, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);

                        $this->fpdf->SetY(-60);
                        $this->fpdf->SetX(330);
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 6") {
                        $logo = 'assets/image/template_image/t3.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);
                        $this->fpdf->SetTextColor(12, 56, 120);
                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(15);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(110);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 245, 260, 40, 45);

                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 75, 220, 0, 50, 'PNG');
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->SetY(-105);
                        $this->fpdf->SetFont('Arial', 'B', 12);
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->SetX(60);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->SetTextColor(12, 56, 120);
                        $this->fpdf->SetY(-90);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 7") {
                        $logo = 'assets/image/template_image/t4.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(45);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(120);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 290, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 100, 275, 0, 60, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);

                        $this->fpdf->SetY(-60);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 8") {
                        $logo = 'assets/image/template_image/t5.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->Ln(15);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(155);
                        $this->fpdf->SetTextColor(0, 0, 0);

                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');

                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 300, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 100, 280, 0, 60, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->SetY(-50);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 9") {
                        $logo = 'assets/image/template_image/t6.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 15, 15, 60);
                        }
                        $this->fpdf->Ln(55);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(110);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 300, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 100, 280, 0, 60, 'PNG');
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->SetY(-40);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->SetY(-50);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 10") {
                        $logo = 'assets/image/template_image/t7.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(80, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(15);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(110);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 370, 300, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 100, 280, 0, 60, 'PNG');
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->SetY(-40);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->SetY(-50);
                        $this->fpdf->SetX(330);
                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 11") {
                        $logo = 'assets/image/template_image/t8.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['530', '373']);
                        $this->fpdf->SetMargins(260, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->SetTextColor(28, 78, 136);
                        $this->fpdf->Ln(40);
                        $this->fpdf->SetX(215);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(120);
                        $this->fpdf->SetTextColor(246, 200, 46);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 335, 300, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 50, 265, 0, 60, 'PNG');

                        $this->fpdf->SetY(-40);
                        $this->fpdf->SetX(30);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->SetY(-50);

                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);

                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    } else if ($fetch->logo == "Template 12") {
                        $logo = 'assets/image/template_image/t9.png';
                        $name = $fetch->name;
                        $company = $fetch->company;
                        $content = $fetch->description;
                        $start = date('F d, Y', strtotime($fetch->from_start_date));
                        $end = date('F d, Y', strtotime($fetch->until_end_date));
                        $organizer = $fetch->organizer;
                        $position = $fetch->position;
                        $url = "https://chart.googleapis.com/chart?cht=qr&chs=50x50&chl={$fetch->certificate_id}&chld=L|1&choe=UTF-8";


                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->AddPage("L", ['500', '373']);
                        $this->fpdf->SetMargins(130, 80, 80, 80);
                        $this->fpdf->Image($logo, 0, 0, 0, 0);
                        $this->fpdf->SetFont('Arial', 'B', 25);

                        if ($fetch->image != null) {
                            $this->fpdf->Image('assets/image/logo/' . $fetch->image, 40, 40, 60);
                        }
                        $this->fpdf->Ln(15);
                        $this->fpdf->Cell(0, 0, $company, 0, 0, 'C');
                        $this->fpdf->SetFont('Arial', 'B', 60);
                        $this->fpdf->Ln(150);
                        $this->fpdf->Cell(0, 0, $name, 0, 0, 'C');
                        $this->fpdf->Ln(20);
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->MultiCell(0, 15, $content, 100, 'C', 0);
                        if ($fetch->from_start_date == $fetch->until_end_date) {
                            $this->fpdf->MultiCell(0, 15, "On this date " . $start, 100, 'C', 0);
                        } else {
                            $this->fpdf->MultiCell(0, 15, "From " . $start . " until " . $end, 100, 'C', 0);
                        }
                        $this->fpdf->Ln(25);
                        $this->fpdf->Image('assets/image/e-signature/' . $fetch->signature, 355, 300, 40, 45);


                        $this->fpdf->Ln(55);
                        $this->fpdf->Image("$url", 50, 280, 0, 50, 'PNG');

                        $this->fpdf->SetY(-35);
                        $this->fpdf->SetTextColor(255, 255, 255);
                        $this->fpdf->SetX(30);
                        $this->fpdf->SetFont('Arial', 'B', 14);
                        $this->fpdf->Cell(0, 0, "$fetch->certificate_id", 0, 0, 'L');
                        $this->fpdf->SetFont('Arial', 'B', 20);
                        $this->fpdf->SetTextColor(0, 0, 0);
                        $this->fpdf->SetY(-50);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, "$organizer", 0, 'C', 0);
                        $this->fpdf->SetFont('Arial', 'B', 15);
                        $this->fpdf->Ln(3);
                        $this->fpdf->SetX(330);
                        $this->fpdf->MultiCell(0, 5, $position, 0, 'C', 0);
                    }
                }
            }
        }
        return view($this->fpdf->Output());

        exit;
    }

    public function index()
    {
        $this->fpdf->Output();
        exit;
    }
}
