<?php


namespace MDrollette\TridentGateway;

class Offline extends Transaction
{
    public function __construct($profileId, $profileKey, $authCode)
    {
        $this->Transaction($profileId, $profileKey);
        $this->RequestFields['auth_code'] = $authCode;
        $this->TranType                   = "O";
    }

    function setStoredData($cardId, $amount)
    {
        $this->RequestFields['card_id']            = $cardId;
        $this->RequestFields['transaction_amount'] = $amount;
    }
}
