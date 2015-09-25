<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter;

interface AdapterInterface
{

    /**
     * @param $gatewayUrl
     */
    public function __construct($gatewayUrl);

    /**
     * @param array $data
     *
     * @return array
     */
    public function sendArrayDataRequest(array $data);

}
