<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRegistrationRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantRegistrationRequest\Dependency\Client\MerchantRegistrationRequestToZedRequestClientInterface;
use Spryker\Client\MerchantRegistrationRequest\Zed\MerchantRegistrationRequestStub;
use Spryker\Client\MerchantRegistrationRequest\Zed\MerchantRegistrationRequestStubInterface;

class MerchantRegistrationRequestFactory extends AbstractFactory
{
    public function createZedMerchantRegistrationRequestStub(): MerchantRegistrationRequestStubInterface
    {
        return new MerchantRegistrationRequestStub(
            $this->getZedRequestClient(),
        );
    }

    public function getZedRequestClient(): MerchantRegistrationRequestToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
