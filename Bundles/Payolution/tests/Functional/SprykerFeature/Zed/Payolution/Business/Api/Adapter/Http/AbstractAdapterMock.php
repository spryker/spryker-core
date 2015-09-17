<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;

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
     * @param array $data
     *
     * @return array
     */
    public function sendArrayDataRequest(array $data)
    {
        $this->requestData = $data;

        if (true === $this->expectSuccess){
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
