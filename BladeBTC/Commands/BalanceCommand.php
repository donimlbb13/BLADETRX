<?php


namespace BladeBTC\Commands;

use BladeBTC\Helpers\Btc;
use BladeBTC\Models\Investment;
use BladeBTC\Models\InvestmentPlan;
use BladeBTC\Models\Users;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class BalanceCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "balance";

	/**
	 * @var string Command Description
	 */
	protected $description = "Display account balance.";

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
		$this->replyWithChatAction(['action' => Actions::TYPING]);


		/**
		 * Verify user
		 */
		$user = new Users($id);
		if ($user->exist() == false) {

			$this->triggerCommand('start');

		} else {

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
				'keyboard'          => $keyboard,
				'resize_keyboard'   => true,
				'one_time_keyboard' => false,
			]);


			/**
			 * Contract list
			 */
			$investment = Investment::getActiveInvestment($user->getTelegramId());
			if (count($investment) > 0) {
				$investment_data = "\n<b>Amount      |   Rate   |   End</b>\n";
				foreach ($investment as $row) {
					$investment_data .= $row->amount . " |   " . InvestmentPlan::getValueByName('base_rate') . "%      |  " . $row->contract_end_date . "\n";
				}
			} else {
				$investment_data = "No active investment, start now with just " . InvestmentPlan::getValueByName("minimum_invest") . " TRX";
			}


			/**
			 * Response
			 */
			$this->replyWithMessage([
				'text'         => "\xF0\x9F\x91\x80 <b>Overview</b> \xF0\x9F\x91\x80\n
<b>Balance</b>
" . TRX::Format($user->getBalance()) . " TRX ( $ " . TRX::FormatUSD($user->getBalance()) . " TRX )\n
<b>Invested</b>
" . TRX::Format($user->getInvested()) . " TRX ( $ " . TRX::FormatUSD($user->getInvested()) . " USD )\n
<b>Reinvested</b>
" . TRX::Format($user->getReinvested()) . " TRX ( $ " . TRX::FormatUSD($user->getReinvested()) . " USD )\n
<b>Profit</b>
" . TRX::Format($user->getProfit()) . " TRX ( $ " . TRX::FormatUSD($user->getProfit()) . " USD )\n
<b>Payout</b>
" . TRX::Format($user->getPayout()) . "  TRX  ( $ " . Bt::FormatUSD($user->getPayout()) . " USD )\n
<b>Commission</b>
" . TRX::Format($user->getCommission()) . "  TRX  ( $ " .  TRX ::FormatUSD($user->getCommission()) . " USD )\n
<b>Deposit (Confirmed)</b>
" . TRX::Format($user->getLastConfirmed()) . "  TRX  ( $ " .  TRX ::FormatUSD($user->getLastConfirmed()) . " USD )\n
<b>Deposit (Lower than minimum invest)</b>
" . TRX::Format($user->getLastConfirmed() - $user->getInvested()) . "  TRX  ( $ " .  TRX ::FormatUSD($user->getLastConfirmed() - $user->getInvested()) . " USD )\n
<b>Active investment</b>
" .  TRX ::Format(Investment::getActiveInvestmentTotal($user->getTelegramId())) . "  TRX  ( $ " .  TRX ::FormatUSD(Investment::getActiveInvestmentTotal($user->getTelegramId())) . " USD )",
				'reply_markup' => $reply_markup,
				'parse_mode'   => 'HTML',
			]);

            /**
             * Response
             */
            $this->replyWithMessage([
                'text'         => "
\xF0\x9F\x95\xA5 <b>Your investment</b> \xF0\x9F\x95\xA5
" . $investment_data,
                'reply_markup' => $reply_markup,
                'parse_mode'   => 'HTML',
            ]);


            /**
             * Response
             */
            $this->replyWithMessage([
                'text'         => "You may start another investment by pressing the <b>Invest</b> button. Your balance will grow according to the base rate.",
                'reply_markup' => $reply_markup,
                'parse_mode'   => 'HTML',
            ]);
		}
	}
}

