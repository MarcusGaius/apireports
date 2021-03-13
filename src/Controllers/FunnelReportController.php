<?php

namespace MarcusGaius\ApiReports\Controllers;

use Google\Client;
use Google_Service_AnalyticsReporting;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Routing\Controller;
use MarcusGaius\ApiReports\Models\FunnelReport;

class FunnelReportController extends Controller
{
    public function funnelData()
    {
        session_start();
        $client = new Client(require storage_path('client_config.php'));
        $client->setAccessToken($_SESSION['access_token']);
        $report = new FunnelReport;
        $g_analytics = new Google_Service_AnalyticsReporting($client);

        foreach ($report->categories as $category => $metrics) {
            foreach ($metrics as $colC => $params) {
                $response = $report->getReport($g_analytics, $params['metric']);
                $response['gesamt'] = array_sum($response);
                foreach ($response as $platform => $value) {
                    $colA = '';
                    if ($platform == 'desktop' && $colC == 'Blog') {
                        $colA = date("W/y");
                    }
                    $temp_values = [
                        $colA,
                        $category,
                        $colC,
                        $params['link'],
                        $platform,
                        $value
                    ];
                    $category = $colC = $params['link'] = '';
                    $values[] = $temp_values;
                }
            }
        }
        $sheets = new Google_Service_Sheets($client);
        $spreadsheetId = '1Wy3ybqCGvSIlLV3hCR78ZTaxHV3FJf1ehKK8mR94Vb4';
        $range = 'ConversionFunnel';
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];


        $headers = $sheets->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        FunnelReport::create(['content' => $values,'headers' => $headers]);
        return response()->json($headers);
    }

    // protected function getReport($g_analytics, $metric)
    // {
    //     $view_id = '187474468';

    //     $dateRange = new Google_Service_AnalyticsData_DateRange();
    //     $dateRange->setStartDate("7daysAgo");
    //     $dateRange->setEndDate("today");

    //     $sessions = new Google_Service_AnalyticsReporting_Metric();
    //     $sessions->setExpression("ga:$metric");
    //     $sessions->setAlias($metric);

    //     $dimensions = new Google_Service_AnalyticsReporting_Dimension();
    //     $dimensions->setName("ga:deviceCategory");


    //     $request = new Google_Service_AnalyticsReporting_ReportRequest();
    //     $request->setViewId($view_id);
    //     $request->setDateRanges($dateRange);
    //     $request->setDimensions(array($dimensions));
    //     $request->setMetrics($sessions);

    //     $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    //     $body->setReportRequests(array($request));

    //     $return = [];
    //     foreach ($g_analytics->reports->batchGet($body)->reports[0]->data->rows as $row) {
    //         $return[$row->dimensions[0]] = (int)$row->metrics[0]->values[0];
    //     }
    //     return $return;
    // }
}
