<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class CSVImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();

            Storage::disk('local')->putFileAs('uploads', $file, $filename);

            $csvData = array_map('str_getcsv', file(storage_path('app/uploads/' . $filename)));

            // Check if the CSV contains the required columns
            $requiredColumns = ["NAME", "EMAIL", "COMPANY"];
            $csvHeader = array_map('strtoupper', $csvData[0]);

            if (count(array_diff($requiredColumns, $csvHeader)) === 0) {
                return redirect()->route('import.preview', ['filename' => $filename]);
            } else {
                // Delete the uploaded file if columns are not as expected
                Storage::disk('local')->delete('uploads/' . $filename);

                return back()->with('error', 'The uploaded CSV file must contain columns: NAME, EMAIL, and COMPANY.');
            }
        }

        return back()->with('error', 'Something went wrong with the CSV import.');
    }

    public function preview($filename)
    {
        $csvData = array_map('str_getcsv', file(storage_path('app/uploads/' . $filename)));

        return view('import.preview', compact('csvData', 'filename'));
    }

    public function submit(Request $request){
        $request->validate([
            'selected_rows' => 'array',
        ]);

        $selectedRows = $request->input('selected_rows', []); // return the selected rows
        // var_dump($selectedRows);

        $bearer_token = "Bearer your_bearer";
        $document_id = "4a030391afc046019da8d2cf78cc9d9253ae875a";
        $sender_email = "your_sender_email";
        $responseData = array();
        foreach ($selectedRows as $email) {
            $response = Http::withHeaders([
                'Authorization' => $bearer_token, // Replace with your actual authorization token
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://api.signnow.com/document/'.$document_id.'/invite', [
                "from" => $sender_email,
                "to" => $email,
            ]);
            
            array_push($responseData, $response->json());
        }

        // var_dump($responseData);
        return view('import.index');
    }
}
