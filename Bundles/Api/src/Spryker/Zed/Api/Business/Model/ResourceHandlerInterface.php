<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

interface ResourceHandlerInterface
{

    /**
     * @param string $resource
     * @param string $method
     * @param mixed $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return mixed
     */
    public function execute($resource, $method, $params);

}
