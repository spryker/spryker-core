<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return array
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
