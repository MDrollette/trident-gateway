<?php


namespace MDrollette\TridentGateway;


class Refund extends Transaction
{
    public function __construct($profileId, $profileKey, $tranId)
    {
        $this->Transaction($profileId, $profileKey);
        $this->RequestFields['transaction_id'] = $tranId;
        $this->TranType                        = "U";
    }

    function setStoredData($cardId, $amount)
    {
        $this->RequestFields['card_id']            = $cardId;
        $this->RequestFields['transaction_amount'] = $amount;
    }

    function setPartialAmount($amount)
    {
        $this->RequestFields['transaction_amount'] = $amount;
    }
}
