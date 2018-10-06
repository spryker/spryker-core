<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Service\Method;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;

/**
 * Service.
 */
class Service extends AbstractMethod implements MethodInterface
{
    /**
     * @const Payment method code.
     */
    public const METHOD = RatepayConstants::METHOD_SERVICE;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    public function profile()
    {
        $request = $this->modelFactory->build(ApiConstants::REQUEST_MODEL_PROFILE);
        $this->mapHeadData();

        return $request;
    }
}
