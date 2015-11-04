<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter;

interface AdapterInterface
{

    /**
     * @param array|string $data
     *
     * @return array
     */
    public function sendRequest($data);

    /**
     * @param array|string $data
     * @param string $user
     * @param string $password
     *
     * @return array
     */
    public function sendAuthorizedRequest($data, $user, $password);

}
