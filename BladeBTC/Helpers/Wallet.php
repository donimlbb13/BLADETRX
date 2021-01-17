<?php

namespace BladeBTC\Helpers;

use BladeBTC\Models\BotSetting;
use BladeBTC\Models\InvestmentPlan;
use stdClass;

/**
 * Class Wallet
 *
 * @package BladeBTC\Helpers
 * @see     https://blockchain.info/api/blockchain_wallet_api
 */
class Wallet
{
    /**
     * Generate payment address
     *
     * @param $telegram_user_id - ID of the current user requesting address
     *
     * @return object - Payment address
     */
    public static function generateAddress($telegram_user_id)
    {

        /**
         * Database connexion
         */
        $db = Database::get();

        /**
         * Select address from users database if exist
         */
        $wallet_address = $db->query("SELECT `investment_address` FROM `users` WHERE `telegram_id` = '$telegram_user_id'")->fetchObject()->investment_address;
        if (!is_null($wallet_address) || !empty($wallet_address)) {
            $data = new stdClass();
            $data->address = $wallet_address;
        }
        else {

            /**
             * Param
             */
            $wallet = BotSetting::getValueByName("wallet_address");
            
          
            /**
             * Request URL
             */
            $url = "https://api.trongrid.io/v1/accounts/$wallet/transactions?only_to=true&only_from=true";

            /**
             * Request
             */
            $data = Curl::get($url);

        }

        return $data;
    }


    /**
     * Get wallet balance
     *
     * @return mixed
     */
    public static function getWalletBalance()
    {

        /**
         * Param
         */
        $wallet = BotSetting::getValueByName("wallet_ad");
       
        

        /**
         * Request URL
         */
        $url ="https://api.trongrid.io/v1/assets/order_by=total_supply,asc

        /**
         * Request
         */
        $data = Curl::get($url);

        return $data->balance;
    }

    /**
     * Send TRX to a specific address
     *
     * @param $to_wallet_address - Wallet address
     * @param $satoshi_amount    - TRX amount
     *
     * @return object - Message
     */
    public static function makeOutgoingPayment($to_wallet_address, $satoshi_amount)
    {
        /**
         * Param
         */
        $wallet = BotSetting::getValueByName("wallet_ad");
       
       

        /**
         * Removing transaction fee
         */
        $send_amount_without_fee = $satoshi_amount - $fee;

        /**
         * Request URL
         */
        $url = "https://api.trongrid.io/v1/contracts/$send_amount_without_fee/events ";

        $data = Curl::get($url);

        return $data;
    }


    /**
     * List address
     *
     * @return mixed
     * @see   
     */
    public static function listAddress()
    {
        /**
         * Param
         */
        $wallet = BotSetting::getValueByName("wallet_ad");
      

        /**
         * Request URL
         */
        $url = "https://api.trongrid.io/v1/transactions/$wallet/events";

        /**
         * Request
         */
        $data = Curl::get($url, true);

        return $data;
    }

    /**
     * Get the amount received and confirmed for an address
     *
     * @param $address
     *
     * @return bool|string
     */
    public static function getConfirmedReceivedByAddress($address)
    {
        $url ="https://api.trongrid.io/v1/accounts/$address/transactions?only_to=true&only_from=true";
	] . InvestmentPlan::getValueByName("required_confirmations");

        $data = Curl::getRaw($url);

        return $data;
    }
}