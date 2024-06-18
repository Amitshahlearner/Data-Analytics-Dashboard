<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Data::query();

            if ($request->has('year')) {
                $query->where('end_year', $request->year);
            }

            if ($request->has('topics')) {
                $query->where('topic', $request->topics);
            }

            // Add more filters as needed

            // Fetch the data from the query
            $data = $query->get();

            // Log the data for debugging
            Log::info('Fetched Data: ', ['data' => $data]);

            // Encode the data in UTF-8 and return as JSON
            $data = $data->map(function ($item) {
                $item->title = utf8_encode($item->title);
                // Encode other fields as needed
                return $item;
            });

            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error Fetching Data: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error Fetching Data'], 500);
        }
    }
}

