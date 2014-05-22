<?php


namespace MDrollette\TridentGateway;


class StoreData extends Transaction
{
    function StoreData($profileId, $profileKey)
    {
        $this->Transaction($profileId, $profileKey);
        $this->TranType = "T";
    }
}
