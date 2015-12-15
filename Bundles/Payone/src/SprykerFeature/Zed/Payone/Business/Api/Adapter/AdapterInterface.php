<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Adapter;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

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
