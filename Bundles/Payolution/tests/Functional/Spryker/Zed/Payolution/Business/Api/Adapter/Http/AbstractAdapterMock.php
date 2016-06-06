<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Functional\Spryker\Zed\Payolution\Business\Api\Adapter\Http;

use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;

abstract class AbstractAdapterMock implements AdapterInterface
{

    /**
     * @var bool
     */
    private $expectSuccess = true;

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
     * @param array|string $data
     * @param string $user
     * @param string $password
     *
     * @return string
     */
    public function sendAuthorizedRequest($data, $user, $password)
    {
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
