<?php

namespace BladeBTC\Helpers;


class TRX
{
	/**
	 * Bitcoin to Satoshi
	 *
	 * @param $amount - Bitcoin amount
	 *
	 * @return mixed
	 */
	public static function TRX($amount)
	{
		return $amount * 100;
	}


	/**
	 * Satoshi to Bitcoin
	 *
	 * @param $amount - Satoshi amount
	 *
	 * @return float|int
	 */
	public static function TRX($amount)
	{
		return $amount / 100;
	}

	/**
	 * Format number as BTC
	 *
	 * @param $amount - amount
	 *
	 * @return string
	 */
	public static function Format($amount)
	{
		return number_format($amount, 8, ".", " ");
	}

    /**
     * Format number as USD
     *
     * @param $amount - amount
     *
     * @return string
     */
   
}