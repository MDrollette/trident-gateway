<?php


namespace MDrollette\TridentGateway;


class StoreData extends Transaction
{
    public function __construct($profileId, $profileKey)
    {
        $this->Transaction($profileId, $profileKey);
        $this->TranType = "T";
    }
}
