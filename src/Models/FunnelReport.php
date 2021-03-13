<?php

namespace MarcusGaius\ApiReports\Models;

use Illuminate\Database\Eloquent\Model;
use Google_Service_AnalyticsData_DateRange;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;

class FunnelReport extends Model
{
    protected $fillable = [
        'content',
        'headers'
    ];
    protected $casts = [
        'headers' => 'array',
        'content' => 'array'
    ];
    
    protected $table = 'funnel_reports';
    
    public $categories = [
        'SEO' => [
            'Blog' => [
                'metric' => 'pageviews',
                'link' => '',
            ],
            'TEC Seite seen' => [
                'metric' => 'pageviews',
                'link' => 'https://www.authentic-charisma.de/traumfrau-eroberer-call/',
            ],
    
        ],
        'YouTube' => [
            'Views' => [
                'metric' => 'pageviews',
                'link' => '',
            ],
            'TEC Seite seen' => [
                'metric' => 'pageviews',
                'link' => 'https://www.authentic-charisma.de/traumfrau-eroberer-call/',
            ],
    
        ]
    ];
    
    public function getReport($g_analytics, $metric)
    {
        $view_id = '187474468';
    
        $dateRange = new Google_Service_AnalyticsData_DateRange();
        $dateRange->setStartDate("7daysAgo");
        $dateRange->setEndDate("today");
    
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:$metric");
        $sessions->setAlias($metric);
    
        $dimensions = new Google_Service_AnalyticsReporting_Dimension();
        $dimensions->setName("ga:deviceCategory");
    
    
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($view_id);
        $request->setDateRanges($dateRange);
        $request->setDimensions(array($dimensions));
        $request->setMetrics($sessions);
    
        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests(array($request));
    
        $return = [];
        foreach ($g_analytics->reports->batchGet($body)->reports[0]->data->rows as $row) {
            $return[$row->dimensions[0]] = (int)$row->metrics[0]->values[0];
        }
        return $return;
    }
}
