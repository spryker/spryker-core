<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRegistrationRequest\Zed;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Client\MerchantRegistrationRequest\Dependency\Client\MerchantRegistrationRequestToZedRequestClientInterface;

class MerchantRegistrationRequestStub implements MerchantRegistrationRequestStubInterface
{
    public function __construct(
        protected MerchantRegistrationRequestToZedRequestClientInterface $zedRequestClient
    ) {
    }

    /**
     * @uses \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface::createMerchantRegistrationRequest()
     */
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        /** @var \Generated\Shared\Transfer\MerchantRegistrationResponseTransfer $merchantRegistrationResponseTransfer */
        $merchantRegistrationResponseTransfer = $this->zedRequestClient->call(
            '/merchant-registration-request/gateway/create-merchant-registration-request',
            $merchantRegistrationRequestTransfer,
        );

        return $merchantRegistrationResponseTransfer;
    }
}
