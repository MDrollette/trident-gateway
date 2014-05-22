<?php


namespace MDrollette\TridentGateway;


class Refund extends Transaction
{
    function Refund($profileId, $profileKey, $tranId)
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
}
