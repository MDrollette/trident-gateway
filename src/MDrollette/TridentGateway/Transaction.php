<?php

namespace MDrollette\TridentGateway;

class Transaction
{
    protected $Post = true;
    protected $ApiHost = "https://api.merchante-solutions.com/mes-api/tridentApi";
    protected $ProxyHost = "";
    protected $ProfileId;
    protected $ProfileKey;
    protected $TranType = "A";
    protected $ErrorMessage;
    protected $ResponseRaw;
    protected $ResponseFields;
    protected $RequestFields;
    protected $RequestFieldNames = ["requester_name", "avs_data", "cardholder_street_address", "cardholder_zip", "cvv2", "transaction_amount", "card_number", "card_exp_date", "transaction_id", "card_present", "reference_number",
        "merchant_name", "merchant_city", "merchant_state", "merchant_zip", "merchant_category_code", "merchant_phone",
        "invoice_number", "tax_amount", "ship_to_zip", "moto_ecommerce_ind", "industry_code", "auth_code", "card_id", "country_code",
        "fx_amount", "fx_rate_id", "currency_code", "rctl_product_level", "echo_customfield",
        "3d_payload", "3d_transaction_id", "client_reference_number",
        "bml_request", "promo_code", "order_num", "order_desc", "amount", "ship_amount", "ip_address", "bill_first_name", "bill_middle_name", "bill_last_name", "bill_addr1", "bill_addr2", "bill_city", "bill_state", "bill_zip", "bill_phone1", "bill_phone2", "bill_email", "ship_first_name", "ship_middle_name", "ship_last_name", "ship_addr1", "ship_addr2", "ship_city", "ship_state", "ship_zip", "ship_phone1", "ship_phone2", "ship_email"];
    protected $url;
    protected $amexLineItems = [];

    function Transaction($profileId = '', $profileKey = '')
    {
        $this->setProfile($profileId, $profileKey);
    }

    function execute()
    {
        if ($this->isValid()) {
            $url = "profile_id=" . $this->ProfileId;
            $url .= "&profile_key=" . $this->ProfileKey;

            $url .= "&transaction_type=" . $this->TranType;
            foreach ($this->RequestFieldNames as $fname) {
                if (isset($this->RequestFields[$fname])) {
                    $url .= "&" . $fname . "=" . $this->RequestFields[$fname];
                }
            }
            // add line items
            if (count($this->amexLineItems)) {
                $url .= '&line_item_count=' . count($this->amexLineItems);
                foreach ($this->amexLineItems as $line) {
                    $url .= '&amex_line_item=' . $line;
                }
            }

            $this->url = $url;

            $this->processRequest();
        }
    }

    function getResponseField($fieldName)
    {
        $retVal = '';
        if (isset($this->ResponseFields[$fieldName])) {
            $retVal = $this->ResponseFields[$fieldName];
        }

        return $retVal;
    }

    function isApproved()
    {
        $errorCode = $this->getResponseField('error_code');
        $retVal    = false;
        if ($errorCode == '000') {
            $retVal = true;
        } else if ($errorCode == '085' && $this->TranType == 'A') {
            $retVal = true;
        }

        return ($retVal);
    }

    function isBlank($value)
    {
        return ($value == "");
    }

    function isValid()
    {
        $retVal             = true; // assume true
        $this->ErrorMessage = "";
        if ($this->isBlank($this->ProfileId) || $this->isBlank($this->ProfileKey)) {
            $this->ErrorMessage = "Missing profile data";
        } else if (isset($this->RequestFields['transaction_amount']) && !is_numeric($this->RequestFields['transaction_amount'])) {
            $this->ErrorMessage = "Amount must be a number";
        }

        return ($this->isBlank($this->ErrorMessage));
    }

    function parseResponse($response)
    {
        $this->ResponseRaw = $response;
        $responseFields    = explode("&", $response);

        foreach ($responseFields as $field) {
            $nameValue                           = explode("=", $field);
            $this->ResponseFields[$nameValue[0]] = $nameValue[1];
        }
    }

    function processRequest()
    {
        $ch = curl_init();

        if ($this->Post) //Use POST
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->url);
            curl_setopt($ch, CURLOPT_URL, $this->ApiHost);
        } else //Use GET(cURL default)
        {
            curl_setopt($ch, CURLOPT_URL, $url = $this->ApiHost . "?" . $this->url);
        }

        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        if (!$this->isBlank($this->ProxyHost)) {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_PROXY, $this->ProxyHost);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $this->parseResponse(curl_exec($ch));
    }

    function setAvsRequest($cardholderStreetAddr, $cardholderZip)
    {
        $this->setRequestField("cardholder_street_address", $cardholderStreetAddr);
        $this->setRequestField("cardholder_zip", $cardholderZip);
    }

    function setHost($host)
    {
        $this->ApiHost = $host;
    }

    function setProfile($profileId, $profileKey)
    {
        $this->ProfileId  = $profileId;
        $this->ProfileKey = $profileKey;
    }

    function setProxyHost($proxyHost)
    {
        $this->ProxyHost = $proxyHost;
    }

    function setRequestField($fieldName, $fieldValue)
    {
        $this->RequestFields[$fieldName] = urlencode($fieldValue);
    }

    function setTransactionData($cardNumber, $expDate, $tranAmount = 0.0)
    {
        $this->RequestFields['card_number']        = $cardNumber;
        $this->RequestFields['card_exp_date']      = $expDate;
        $this->RequestFields['transaction_amount'] = $tranAmount;
    }

    function setPost($bool)
    {
        $this->Post = $bool;
    }

    function setDynamicData($name, $city, $state, $zip, $mcc, $phone)
    {
        $this->RequestFields['merchant_name']          = $name;
        $this->RequestFields['merchant_city']          = $city;
        $this->RequestFields['merchant_state']         = $state;
        $this->RequestFields['merchant_zip']           = $zip;
        $this->RequestFields['merchant_category_code'] = $mcc;
        $this->RequestFields['merchant_phone']         = $phone;
    }

    function addAmexLineItem($description, $quantity, $cost)
    {
        $this->amexLineItems[] = sprintf('%s<|>%s<|>%s', substr($description, 0, 40), $quantity, $cost);
    }
}

