<?php

namespace App\Http\Controllers;

use App\Http\Requests\PDFGenerateRequest;
use App\Mail\SendCertificate;
use App\PDF;
use Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use ZipArchive;

class PDFGeneratorController extends Controller
{

    public function generate(PDFGenerateRequest $request)
    {
        $PDFpath = $request->file('pdf-file')->storeAs(
            'pdfs', strtotime(now()) . '.pdf'
        );
        $CSVpath = $request->file('csv-file')->storeAs(
            'csvs', strtotime(now()) . '.csv'
        );
        $uuid = Str::random(15);
        $public_dir = public_path("certificates/" . $uuid);
        mkdir($public_dir);

//        dd(Storage::path($CSVpath));
        $rows = SimpleExcelReader::create(Storage::path($CSVpath))->getRows();

        $rows->each(function (array $row) use ($PDFpath, $request, $uuid) {

            $pdf = new PDF();
            $pdf->setSourceFile(Storage::path($PDFpath));
            $pdf->AddPage();
            $pdf->SetFont("helvetica", "B", 20);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Text($request->get('name-x'), $request->get('name-y'), $row['name']);
            $pdf->Text(150, 116, $row['class']);
            $pdf->Text(150, 134, $row['course']);
            $pdf->SetFont("helvetica", "B", 15);
            $pdf->Text($request->get('date-x'), $request->get('date-y'), $row['end_date']);
            $certificatePath = public_path("certificates/" . $uuid . "/" . $row['name'] . ".pdf");
            $pdf->Output($certificatePath, 'F');

            if ($request->has('email-body')) {

                $emailBody = $request->get('email-body');
                foreach ($row as $key => $value) {
                    $emailBody = str_replace('{' . strtoupper($key) . '}', $value, $emailBody);
                }
                Mail::to($row['email'])
                    ->queue(new SendCertificate($row, $emailBody, $certificatePath));
            }

        });

        $zip = new ZipArchive;
        if ($zip->open($public_dir . '/' . 'Certificates.zip', ZipArchive::CREATE) === TRUE) {
            // Add File in ZipArchive
            foreach (glob($public_dir . "/*") as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        return response()->download($public_dir . '/Certificates.zip', 'Certificates.zip');


    }
}
