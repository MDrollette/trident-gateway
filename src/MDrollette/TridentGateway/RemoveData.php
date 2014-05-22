<?php

namespace MDrollette\TridentGateway;

class RemoveData extends Transaction
{
    public function __construct($profileId, $profileKey, $cardId)
    {
        $this->Transaction($profileId, $profileKey);
        $this->RequestFields['card_id'] = $cardId;
        $this->TranType                 = "X";
    }
}
