<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Data;
use League\Csv\Reader;

class DataSeeder extends Seeder
{
    public function run()
    {
        $csv = Reader::createFromPath(storage_path('app/data.csv'), 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(',');

        foreach ($csv->getRecords() as $record) {
            Data::create([
                'end_year' => $this->sanitizeString($record['end_year']),
                'intensity' => $this->sanitizeString($record['intensity']),
                'sector' => $this->sanitizeString($record['sector']),
                'topic' => $this->sanitizeString($record['topic']),
                'insight' => $this->sanitizeString($record['insight']),
                'url' => $this->sanitizeString($record['url']),
                'region' => $this->sanitizeString($record['region']),
                'start_year' => $this->sanitizeString($record['start_year']),
                'impact' => $this->sanitizeString($record['impact']),
                'added' => $this->sanitizeString($record['added']),
                'published' => $this->sanitizeString($record['published']),
                'city' => $this->sanitizeString($record['city']),
                'country' => $this->sanitizeString($record['country']),
                'relevance' => $this->sanitizeString($record['relevance']),
                'pestle' => $this->sanitizeString($record['pestle']),
                'source' => $this->sanitizeString($record['source']),
                'title' => $this->sanitizeString($record['title']),
                'likelihood' => $this->sanitizeString($record['likelihood']),
            ]);
        }
    }

    private function sanitizeString($string)
    {
        return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }
}

