<?php

namespace MarcusGaius\ApiReports\Controllers;

use Exception;
use Google\Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_YouTube;
use Google_Service_YouTubeAnalytics;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MarcusGaius\ApiReports\Models\Report;

class YouTubeReportController extends Controller
{
    public function ytvd(Request $request)
    {
        session_start();
        $client = new Client(require storage_path('client_config.php'));
        $client->setAccessToken($_SESSION['access_token']);
        $yt_analytics = new Google_Service_YouTubeAnalytics($client);

        $queryParams = [
            'endDate' => date("Y-m-d"),
            'ids' => 'channel==MINE',
            'metrics' => 'views',
            'dimensions' => 'video',
            'startDate' => '2019-03-01',
            'maxResults' => '200',
            'sort' => '-views',
        ];

        $rows = $yt_analytics->reports->query($queryParams)->rows;

        try {
            foreach (array_chunk(array_column($rows, 0), 50) as $video_id) {
                $items =  (new Google_Service_YouTube($client))
                    ->videos
                    ->listVideos('snippet', [
                        'id' => implode(',', $video_id),
                    ])
                    ->items;
                foreach ($items as $item) {
                    array_unshift($rows[array_search($item->id, array_column($rows, 0))], $item->snippet->title);
                };
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }

        $sheets = new Google_Service_Sheets($client);
        $spreadsheetId = '1Wy3ybqCGvSIlLV3hCR78ZTaxHV3FJf1ehKK8mR94Vb4';
        $range = 'Test!A2';
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $rows
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];
        $response = $sheets->spreadsheets_values->append($spreadsheetId, $range, $body, $params);;
        Report::create(['content' => $rows,'headers' => $response]);
        return response()->json($response);
    }
}
