<?php


namespace MDrollette\TridentGateway;


class Void extends Transaction
{
    function Void($profileId, $profileKey, $tranId)
    {
        $this->Transaction($profileId, $profileKey);
        $this->RequestFields['transaction_id'] = $tranId;
        $this->TranType                        = "V";
    }

    function setStoredData($cardId, $amount)
    {
        $this->RequestFields['card_id']            = $cardId;
        $this->RequestFields['transaction_amount'] = $amount;
    }
}
