<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

 Spryker\Zed\Braintree\Business\Api\Adapter;

/**
 * (c) Spryker Systems GmbH copyright protected.
 */
class BraintreeAdapter implements \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface
{

    public function createCustomer($transfer)
    {
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
    }

    /**
     * @param array|string $data
     *
     * @return string
     */
    public function sendRequest($data)
    {
        // TODO: Implement sendRequest() method.
    }

    /**
     * @param array|string $data
     * @param string $user
     * @param string $password
     *
     * @return string
     */
    public function sendAuthorizedRequest($data, $user, $password)
    {
        // TODO: Implement sendAuthorizedRequest() method.
    }

}
