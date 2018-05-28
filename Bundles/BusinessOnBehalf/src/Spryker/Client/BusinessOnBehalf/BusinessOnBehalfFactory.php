<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf;

use Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface;
use Spryker\Client\BusinessOnBehalf\Zed\BusinessOnBehalfStub;
use Spryker\Client\BusinessOnBehalf\Zed\BusinessOnBehalfStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class BusinessOnBehalfFactory extends AbstractFactory
{
    /**
     * @return BusinessOnBehalfStubInterface
     */
    public function createZedBusinessOnBehalfStub(): BusinessOnBehalfStubInterface
    {
        return new BusinessOnBehalfStub($this->getZedRequestClient());
    }

    /**
     * @return BusinessOnBehalfToZedRequestClientInterface
     */
    protected function getZedRequestClient(): BusinessOnBehalfToZedRequestClientInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
