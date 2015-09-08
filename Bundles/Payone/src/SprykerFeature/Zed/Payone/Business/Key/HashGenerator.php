<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Key;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;

class HashGenerator implements HashGeneratorInterface
{

    /**
     * @var HashInterface
     */
    protected $hashProvider;
    /**
     * @var array
     */
    protected $hashParameters = [
        'mid',
        'amount',
        'productid',
        'aid',
        'currency',
        'accessname',
        'portalid',
        'due_time',
        'accesscode',
        'mode',
        'storecarddata',
        'access_expiretime',
        'request',
        'checktype',
        'access_canceltime',
        'responsetype',
        'addresschecktype',
        'access_starttime',
        'reference',
        'consumerscoretype',
        'access_period',
        'userid',
        'invoiceid',
        'access_aboperiod',
        'customerid',
        'invoiceappendix',
        'access_price',
        'param',
        'invoice_deliverymode',
        'access_aboprice',
        'narrative_text',
        'eci',
        'access_vat',
        'successurl',
        'settleperiod',
        'errorurl',
        'settletime',
        'backurl',
        'vaccountname',
        'exiturl',
        'vreference',
        'clearingtype',
        'encoding',
    ];

    /**
     * @param HashInterface $hashProvider
     */
    public function __construct(HashInterface $hashProvider)
    {
        $this->hashProvider = $hashProvider;
    }

    /**
     * @param AbstractRequestContainer $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateParamHash(AbstractRequestContainer $request, $securityKey)
    {
        $hashString = '';
        $requestData = $request->toArray();
        sort($this->hashParameters);
        foreach ($this->hashParameters as $key) {
            if (!array_key_exists($key, $requestData)) {
                continue;
            }
            $hashString .= $requestData[$key];
        }
        $hashString .= $securityKey;

        return $this->hash($hashString);
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function hash($string)
    {
        return $this->hashProvider->hash($string);
    }

}
