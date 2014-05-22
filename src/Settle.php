<?php

namespace MDrollette\TridentGateway;

class Settle extends Transaction
{
    function Settle($profileId, $profileKey, $tranId, $settleAmount = 0)
    {
        $this->Transaction($profileId, $profileKey);
        $this->RequestFields['transaction_id']     = $tranId;
        $this->RequestFields['transaction_amount'] = $settleAmount;
        $this->TranType                            = "S";
    }

    function setSettlementAmount($settleAmount)
    {
        $this->RequestFields['transaction_amount'] = $settleAmount;
    }
}
