<?php

namespace App\Http\Controllers;

use DateTime;

class EST662Controller extends Controller
{
    const APPLY_TIME = '05:00:00';
	const DAILY_PERIOD = 'daily';
	const WEEKLY_PERIOD = 'weekly';
	const MONTHLY_PERIOD = 'monthly';

	/**
	 * Get the aggregation period for therapist rankings
	 * @param string $type
	 * @param string $view_time
	 * @return array
	 */
    public function getLabelIndex(string $type = self::DAILY_PERIOD, string $view_time = ''): array {
        $view_time = $view_time ? new DateTime($view_time) : new DateTime();

		switch ($type)
		{
			case self::WEEKLY_PERIOD:
				return $this->get_time_by_weekly_period($view_time);
			case self::MONTHLY_PERIOD:
				return $this->get_time_by_monthly_period($view_time);
			case self::DAILY_PERIOD:
			default:
				return $this->get_time_by_daily_period($view_time);
		}
    }

	/**
	 * Get the aggregation period for daily rankings
	 * @param DateTime $view_time
	 * @return array
	 */
	private function get_time_by_daily_period(DateTime $view_time): array
	{
		$is_before_apply_time = $view_time->format('H:i:s') < self::APPLY_TIME;
		$update_at = (clone $view_time)->setTime(5, 0, 0);

		if ($is_before_apply_time)
		{
			$update_at->modify('-1 day');
		}

		$summary_from = (clone $update_at)->modify('-1 day')->setTime(0, 0, 0);
		$summary_to = (clone $summary_from)->setTime(23, 59, 59);

		return [
			'update_at' => $update_at,
			'summary_from' => $summary_from,
			'summary_to' => $summary_to
		];
	}

	/**
	 * Get the aggregation period for weekly rankings
	 * @param DateTime $view_time
	 * @return array
	 */
	private function get_time_by_weekly_period(DateTime $view_time): array
	{
		$monday_apply = (clone $view_time)->modify('this week monday')->setTime(5, 0, 0);

		$update_at = ($view_time < $monday_apply)
			? (clone $monday_apply)->modify('-7 days')
			: $monday_apply;

		$summary_from = (clone $update_at)->modify('-7 days')->setTime(0, 0, 0);
		$summary_to = (clone $update_at)->modify('-1 day')->setTime(23, 59, 59);

		return [
			'update_at' => $update_at,
			'summary_from' => $summary_from,
			'summary_to' => $summary_to
		];
	}

	/**
	 * Get the aggregation period for monthly rankings
	 * @param DateTime $view_time
	 * @return array
	 */
	private function get_time_by_monthly_period(DateTime $view_time): array
	{
		$month_start = (clone $view_time)->modify('first day of this month')->setTime(5, 0, 0);
		
		$update_at = ($view_time < $month_start)
			? (clone $month_start)->modify('-1 month')
			: $month_start;
		
		$summary_from = (clone $update_at)->modify('-1 month')->setTime(0, 0, 0);
		$summary_to = (clone $update_at)->modify('-1 day')->setTime(23, 59, 59);

		return [
			'update_at' => $update_at,
			'summary_from' => $summary_from,
			'summary_to' => $summary_to
		];
	}
}
