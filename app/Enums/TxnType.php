<?php

namespace App\Enums;

enum TxnType: string
{
    case Deposit = 'deposit';
    case Subtract = 'subtract';
    case ManualDeposit = 'manual_deposit';
    case SendMoney = 'send_money';
    case Exchange = 'exchange';
    case Referral = 'referral';
    case SignupBonus = 'signup_bonus';
    case Bonus = 'bonus';
    case Withdraw = 'withdraw';
    case WithdrawAuto = 'withdraw_auto';
    case ReceiveMoney = 'receive_money';
    case TaskReward = 'task_reward';
    case Refund = 'refund';
}
