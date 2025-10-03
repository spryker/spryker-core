<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Creator;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToMerchantUserFacadeInterface;

class MerchantUserCreator implements MerchantUserCreatorInterface
{
    public function __construct(
        protected MerchantRegistrationRequestToMerchantUserFacadeInterface $merchantUserFacade
    ) {
    }

    /**
     * @return void
     */
    public function createMerchantUser(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer,
        MerchantTransfer $merchantTransfer
    ): void {
        $this->merchantUserFacade->createMerchantUser(
            (new MerchantUserTransfer())
                ->setIdMerchant($merchantTransfer->getIdMerchant())
                ->setUser($this->createUserTransfer($merchantRegistrationRequestTransfer)),
        );
    }

    protected function createUserTransfer(MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer): UserTransfer
    {
        return (new UserTransfer())
            ->setUsername($merchantRegistrationRequestTransfer->getEmail())
            ->setEmail($merchantRegistrationRequestTransfer->getEmail())
            ->setFirstName($merchantRegistrationRequestTransfer->getContactPersonFirstName())
            ->setLastName($merchantRegistrationRequestTransfer->getContactPersonLastName());
    }
}
