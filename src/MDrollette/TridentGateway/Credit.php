<?php

namespace MDrollette\TridentGateway;

class Credit extends Transaction
{
    public function __construct($profileId, $profileKey)
    {
        $this->Transaction($profileId, $profileKey);
        $this->TranType = "C";
    }

    function setStoredData($cardId, $amount)
    {
        $this->RequestFields['card_id']            = $cardId;
        $this->RequestFields['transaction_amount'] = $amount;
    }
}
