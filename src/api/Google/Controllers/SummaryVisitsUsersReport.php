<?php

namespace PmAnalyticsPackage\api\Google\Controllers;

use PmAnalyticsPackage\api\Google\API\SummaryVisitsUsersAPI;

class SummaryVisitsUsersReport extends AnalyticsController
{
    public function getAnalyticsData($dealerCode, $startDate, $endDate)
    {
        $format = 'Y-m-d';
        $startDate = \DateTime::createFromFormat($format, $startDate);
        $endDate = \DateTime::createFromFormat($format, $endDate);
        $dealer = self::dealerType($dealerCode);

        $ga = new SummaryVisitsUsersAPI(
            $dealer,
            $startDate,
            $endDate,
            $dealer[0]['account_name'],
            self::$reportType
        );

        $output = [
            'sessions' => 0,
            'users' => 0,
            'newUsers' => 0,
            'pageviews' => 0,
            'sessionDuration' => 0,
        ];

        foreach ($ga->analyticsArray as $device => $networks) {
            foreach ($networks as $network => $data) {
                foreach ($output as $key => $value) {
                    $output[$key] += $data[$key] ?? 0;
                }
            }
        }
        $output['google_profile_id'] = $dealer[0]['google_profile_id'];
        $output['account_name'] = $dealer[0]['account_name'];
        return $output;
    }
}
