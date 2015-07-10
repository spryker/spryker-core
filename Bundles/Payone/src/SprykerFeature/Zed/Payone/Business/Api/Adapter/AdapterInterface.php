<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Adapter;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface AdapterInterface
{

    /**
     * @param array $params
     *
     * @return array
     */
    public function sendRawRequest(array $params);

    /**
     * @param AbstractRequestContainer $container
     *
     * @return mixed
     */
    public function sendRequest(AbstractRequestContainer $container);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getRawResponse();

}
