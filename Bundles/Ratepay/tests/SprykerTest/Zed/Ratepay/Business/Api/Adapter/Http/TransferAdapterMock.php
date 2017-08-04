<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http;

class TransferAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return $this->requestData;
    }

    /**
     * @return array
     */
    public function getFailureResponse()
    {
        return $this->requestData;
    }

}
