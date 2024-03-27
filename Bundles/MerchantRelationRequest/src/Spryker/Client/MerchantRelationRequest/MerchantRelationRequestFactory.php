<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantRelationRequest\Dependency\Client\MerchantRelationRequestToZedRequestClientInterface;
use Spryker\Client\MerchantRelationRequest\Zed\MerchantRelationRequestStub;
use Spryker\Client\MerchantRelationRequest\Zed\MerchantRelationRequestStubInterface;

class MerchantRelationRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantRelationRequest\Zed\MerchantRelationRequestStubInterface
     */
    public function createMerchantRelationRequestStub(): MerchantRelationRequestStubInterface
    {
        return new MerchantRelationRequestStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\MerchantRelationRequest\Dependency\Client\MerchantRelationRequestToZedRequestClientInterface
     */
    public function getZedRequestClient(): MerchantRelationRequestToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
