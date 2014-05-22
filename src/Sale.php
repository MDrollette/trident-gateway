<?php


namespace MDrollette\TridentGateway;


class Sale extends Transaction
{
    function Sale($profileId, $profileKey)
    {
        $this->Transaction($profileId, $profileKey);
        $this->TranType = "D";
    }

    function setStoredData($cardId, $amount)
    {
        $this->RequestFields['card_id']            = $cardId;
        $this->RequestFields['transaction_amount'] = $amount;
    }

    function setFXData($amt, $rid, $curr)
    {
        $this->RequestFields['fx_amount']     = $amt;
        $this->RequestFields['fx_rate_id']    = $rid;
        $this->RequestFields['currency_code'] = $curr;
    }

    function setEcommInd($ind)
    {
        $this->RequestFields['moto_ecommerce_ind'] = $ind;
    }
}
