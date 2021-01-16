<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\BotSetting;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class InfoCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "info";

    /**
     * @var string Command Description
     */
    protected $description = "Info menu.";

    /**
     * @inheritdoc
     */
    public function handle($arguments)
    {

        /**
         * Chat data
         */
        $id = $this->update->getMessage()->getFrom()->getId();


        /**
         * Display Typing...
         */
        $this->replyWithChatAction([ 'action' => Actions::TYPING ]);


        /**
         * Verify user
         */
        $user = new Users($id);
        if ($user->exist() == false) {

            $this->triggerCommand('start');

        }
        else {

            /**
             * Keyboard
             */
            $keyboard = [
                ["My balance " . TRX::Format($user->getBalance()) . " \xF0\x9F\x92\xB0"],
				["Invest \xF0\x9F\x92\xB5", "Withdraw \xE2\x8C\x9B"],
				["Reinvest \xE2\x86\xA9", "Help \xE2\x9D\x93"],
				["Referrals \xF0\x9F\x91\xAB","live pays \xF0\x9F\x92\xB5"],
            ];

            $reply_markup = $this->telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
            ]);

            /**
             * Response
             */


            $this->replyWithMessage([
                'text' => "<b>First steps</b>🎽

Your perfect start.

 New New: Tron HuntX
🗣 Status: ✅ Paying<b>
</b>
<b>📆 Date Started: Jan 8, 2021</b>

<b>✅ BotLink: @TronHuntXPay</b>

<b>🎁 Daily Free TRX</b>
<b>
💹 Investment Plan Daily:
- 10-200 trx : 120%
- 201-500 trx : 160%</b>
<b>
💹 Investment Plan (per 2 days)
- 501-unlimited : 200%
</b>
* Commission Rate Bot
- Level 1 : 10%
- Level 2 : 5%
- Level 3 : 3%
<b>
➕ Minimum Deposit: 10 TRX
➖ Minimum Withdraw: 10 TRX
🔁 Minimum Reinvest: 10 TRX
📛 Fees: No
✅ Instant Withdrawal
👩‍👩‍👧‍👧 Ref/Bonus: 3 level up to 10%!
</b>
💰 Payment Method: TRX
<b>
🤖 BotLink: @TronHuntX </b>
<b>Deposit - Invest</b> 💵



✏ " . BotSetting::getValueByName("support_chat_id"),
                'reply_markup' => $reply_markup,
                'parse_mode' => 'HTML',
            ]);

        }
    }
}