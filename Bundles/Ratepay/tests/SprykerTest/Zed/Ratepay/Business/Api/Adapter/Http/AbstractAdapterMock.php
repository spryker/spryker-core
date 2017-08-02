<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;

abstract class AbstractAdapterMock implements AdapterInterface
{

    /**
     * @var bool
     */
    protected $expectSuccess = true;

    /**
     * @var array
     */
    protected $requestData = [];

    /**
     * @return $this
     */
    public function expectSuccess()
    {
        $this->expectSuccess = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function expectFailure()
    {
        $this->expectSuccess = false;

        return $this;
    }

    /**
     * @param array|string $data
     *
     * @return string
     */
    public function sendRequest($data)
    {
        $this->requestData = $data;

        if ($this->expectSuccess === true) {
            return $this->getSuccessResponse();
        }

        return $this->getFailureResponse();
    }

    /**
     * @return array
     */
    abstract public function getSuccessResponse();

    /**
     * @return array
     */
    abstract public function getFailureResponse();

}
