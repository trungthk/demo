<?php

namespace Tests\Feature;

use App\Http\Controllers\EST662Controller;
use Tests\TestCase;

class EST662Test extends TestCase
{
    /**
     * Get instance of EST662Controller
     *
     * @return EST662Controller
     */
    public function getEst662Controller() {
        return new EST662Controller();
    }

    /**
     * Test all test case of daily ranking on index page
     *
     * @return void
     */
    public function test_mix_daily() {
        $testCases = [
            ['2025-09-04 04:00:00', '2025-09-03', '2025-09-02'],
            ['2025-09-04 06:00:00', '2025-09-04', '2025-09-03'],
            ['2025-09-03 23:00:00', '2025-09-03', '2025-09-02'],
            ['2025-09-01 04:00:00', '2025-08-31', '2025-08-30'],
            ['2025-09-01 06:00:00', '2025-09-01', '2025-08-31'],
            ['2025-08-31 23:00:00', '2025-08-31', '2025-08-30'],
        ];

        foreach ($testCases as [$input, $expectedUpdateAt, $expectedSummaryFrom]) {
            $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $input);

            $this->assertEquals($expectedUpdateAt, $result['update_at']->format('Y-m-d'));
            $this->assertEquals($expectedSummaryFrom, $result['summary_from']->format('Y-m-d'));
        }
    }

    /**
     * Test all test case of weekly ranking on index page
     *
     * @return void
     */
    public function test_mix_weekly() {
        $testCases = [
            ['2025-09-04 04:00:00', '2025-09-01', '2025-08-25', '2025-08-31'],
            ['2025-09-04 06:00:00', '2025-09-01', '2025-08-25', '2025-08-31'],
            ['2025-09-01 06:00:00', '2025-09-01', '2025-08-25', '2025-08-31'],
            ['2025-09-01 04:00:00', '2025-08-25', '2025-08-18', '2025-08-24'],
        ];

        foreach ($testCases as [$input, $expectedUpdateAt, $expectedSummaryFrom, $expectedSummaryTo]) {
            $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $input);

            $this->assertEquals($expectedUpdateAt, $result['update_at']->format('Y-m-d'));
            $this->assertEquals($expectedSummaryFrom, $result['summary_from']->format('Y-m-d'));
            $this->assertEquals($expectedSummaryTo, $result['summary_to']->format('Y-m-d'));
        }
    }

    // ====================== Test case for daily ====================== //
    /**
     * No 1
     * Test view result daily at 04:59 AM 1/5/2025 (thursday)
     * └デイリー：4/30更新（4/29集計） Daily: cập nhật 30/4 (tổng kết 29/4)
     * @return void
     */
    public function test_daily_before_5am() {
        $datetime = '2025-05-01 04:59:00';
        $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $datetime);

        $this->assertEquals('2025-04-30', $result['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-29', $result['summary_from']->format('Y-m-d'));
    }

    /**
     * No 2
     * Test view result daily at 05:15 AM 1/5/2025 (thursday)
     * └週間：4/28更新（4/21～4/27集計）Weekly: cập nhật 28/4 (tổng kết 21/4~27/4)
     * @return void
     */
    public function test_daily_after_5am() {
        $datetime = '2025-05-01 05:15:00';
        $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $datetime);

        $this->assertEquals('2025-05-01', $result['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-30', $result['summary_from']->format('Y-m-d'));
    }
    // ====================== End Test case for daily ====================== //


    // ====================== Test case for weekly ====================== //
    /**
     * No 3
     * Test view result weekly at 04:59 AM 1/5/2025 (thursday)
     * └週間：4/28更新（4/21～4/27集計）Weekly: cập nhật 28/4 (tổng kết 21/4~27/4)
     * @return void
     */
    public function test_weekly_before_5am() {
        $datetime = '2025-05-01 04:59:00';
        $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        $this->assertEquals('2025-04-28', $result['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-21', $result['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-04-27', $result['summary_to']->format('Y-m-d'));
    }

    /**
     * No 4
     * Test view result weekly at 05:15 AM 5/5/2025 (monday)
     * └週間：5/5更新（4/28～5/4集計）Weekly: cập nhật 5/5 (tổng kết 28/4~4/5)
     * @return void
     */
    public function test_weekly_after_5am_is_monday() {
        $datetime = '2025-05-05 05:15:00'; // Monday
        $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        $this->assertEquals('2025-05-05', $result['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-28', $result['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-05-04', $result['summary_to']->format('Y-m-d'));
    }

    /**
     * No 5
     * Test view result weekly at 05:15 AM 1/5/2025 (Thursday)
     * └週間：4/28更新（4/21～4/27集計）Weekly: cập nhật 28/4 (tổng kết 21/4~27/4)
     * @return void
     */
    public function test_weekly_after_5am_not_monday() {
        $datetime = '2025-05-01 05:15:00'; // Thursday
        $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        $this->assertEquals('2025-04-28', $result['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-21', $result['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-04-27', $result['summary_to']->format('Y-m-d'));
    }
    // ====================== End Test case for weekly ====================== //

    // ====================== Test type ====================== //
    /**
     * No 6
     * Test type in defined
     * @return void
     */
    public function test_type_in_defined() {
        $type = 'yearly';
        $datetime = '2025-05-01 04:59:00'; // Thursday
        $result = $this->getEst662Controller()->getLabelIndex($type, $datetime);

        $this->assertNotContains($type, [EST662Controller::MONTHLY, EST662Controller::WEEKLY, EST662Controller::DAILY]);
        $this->assertEmpty($result);
    }
    // ====================== End Test type ====================== //

    // ====================== Test case mixed ====================== //
    /**
     * No 7
     * Test view result daily and weekly at 05:15 AM 5/5/2025 (monday)
     *
     * @return void
     */
    public function test_after_5am_is_monday() {
        $datetime = '2025-05-05 05:15:00'; // Monday
        $result_daily = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $datetime);
        $result_weekly = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        // Daily
        $this->assertEquals('2025-05-05', $result_daily['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-05-04', $result_daily['summary_from']->format('Y-m-d'));

        // Weekly
        $this->assertEquals('2025-05-05', $result_weekly['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-28', $result_weekly['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-05-04', $result_weekly['summary_to']->format('Y-m-d'));
    }

    /**
     * No 8
     * Test view result daily and weekly at 05:15 AM 1/5/2025 (thursday)
     *
     * @return void
     */
    public function test_after_5am_not_monday() {
        $datetime = '2025-05-01 05:15:00'; // Thursday
        $result_daily = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $datetime);
        $result_weekly = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        // Daily
        $this->assertEquals('2025-05-01', $result_daily['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-30', $result_daily['summary_from']->format('Y-m-d'));

        // Weekly
        $this->assertEquals('2025-04-28', $result_weekly['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-21', $result_weekly['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-04-27', $result_weekly['summary_to']->format('Y-m-d'));
    }

    /**
     * No 9
     *
     * @return void
     */
    public function test_before_5am_is_monday() {
        $datetime = '2025-05-05 04:59:00'; // Monday
        $result_daily = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $datetime);
        $result_weekly = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        // Daily
        $this->assertEquals('2025-05-04', $result_daily['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-05-03', $result_daily['summary_from']->format('Y-m-d'));

        // Weekly
        $this->assertEquals('2025-04-28', $result_weekly['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-21', $result_weekly['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-04-27', $result_weekly['summary_to']->format('Y-m-d'));
    }

    /**
     * No 10
     * Test view result daily and weekly at 04:59 AM 1/5/2025 (thursday)
     *
     * @return void
     */
    public function test_before_5am_not_monday() {
        $datetime = '2025-05-01 04:59:00'; // Thursday
        $result_daily = $this->getEst662Controller()->getLabelIndex(EST662Controller::DAILY, $datetime);
        $result_weekly = $this->getEst662Controller()->getLabelIndex(EST662Controller::WEEKLY, $datetime);

        // Daily
        $this->assertEquals('2025-04-30', $result_daily['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-29', $result_daily['summary_from']->format('Y-m-d'));

        // Weekly
        $this->assertEquals('2025-04-28', $result_weekly['update_at']->format('Y-m-d'));
        $this->assertEquals('2025-04-21', $result_weekly['summary_from']->format('Y-m-d'));
        $this->assertEquals('2025-04-27', $result_weekly['summary_to']->format('Y-m-d'));
    }
    // ====================== End Test case mixed ====================== //


    // // ====================== Test case for monthly ====================== //
    // /**
    //  * No 11
    //  * Test view result monthly at 05:15 AM 6/5/2025 (tuesday)
    //  * └月間：5/1更新（4/1～4/30集計）Monthly: cập nhật 1/5 (tổng kết 1/4~30/4)
    //  * @return void
    //  */
    // public function test_monthly_after_5am() {
    //     $datetime = '2025-05-06 05:15:00'; // Tuesday
    //     $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::MONTHLY, $datetime);

    //     $this->assertNull($result['update_at']);
    //     $this->assertNull($result['summary_from']);
    //     $this->assertNull($result['summary_to']);
    // }

    // /**
    //  * No 12
    //  * Test view result monthly at 04:59 AM 1/5/2025 (thursday)
    //  * └月間：4/1更新（3/1～3/31集計）Monthly: cập nhật 1/4 (tổng kết 1/3~31/3)
    //  * @return void
    //  */
    // public function test_monthly_before_5am_is_first_month() {
    //     $datetime = '2025-05-01 04:59:00'; // Thursday, first day of month
    //     $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::MONTHLY, $datetime);

    //     $this->assertNull($result['update_at']);
    //     $this->assertNull($result['summary_from']);
    //     $this->assertNull($result['summary_to']);
    // }

    // /**
    //  * No 13
    //  * Test view result monthly at 04:59 AM 2/5/2025 (friday)
    //  * └月間：4/1更新（3/1～3/31集計）Monthly: cập nhật 1/4 (tổng kết 1/3~31/3)
    //  * @return void
    //  */
    // public function test_monthly_before_5am_not_first_month() {
    //     $datetime = '2025-05-02 04:59:00'; // Friday, not first day of month
    //     $result = $this->getEst662Controller()->getLabelIndex(EST662Controller::MONTHLY, $datetime);

    //     $this->assertNull($result['update_at']);
    //     $this->assertNull($result['summary_from']);
    //     $this->assertNull($result['summary_to']);
    // }
    // // ====================== End Test case for monthly ====================== //
}
