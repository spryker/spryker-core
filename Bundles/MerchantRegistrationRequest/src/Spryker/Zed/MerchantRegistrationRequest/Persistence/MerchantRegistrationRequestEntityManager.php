<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Persistence;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestPersistenceFactory getFactory()
 */
class MerchantRegistrationRequestEntityManager extends AbstractEntityManager implements MerchantRegistrationRequestEntityManagerInterface
{
    public function createMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer {
        $merchantRegistrationRequestEntity = $this->getFactory()
            ->createMerchantRegistrationRequestMapper()
            ->mapMerchantRegistrationRequestTransferToMerchantRegistrationRequestEntity($merchantRegistrationRequestTransfer);

        $merchantRegistrationRequestEntity->save();

        return $this->getFactory()
            ->createMerchantRegistrationRequestMapper()
            ->mapMerchantRegistrationRequestEntityToMerchantRegistrationRequestTransfer(
                $merchantRegistrationRequestEntity,
                $merchantRegistrationRequestTransfer,
            );
    }

    public function updateMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer {
        $merchantRegistrationRequestEntity = $this->getFactory()
            ->createSpyMerchantRegistrationRequestQuery()
            ->filterByIdMerchantRegistrationRequest($merchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest())
            ->findOne();

        if (!$merchantRegistrationRequestEntity) {
            return $merchantRegistrationRequestTransfer;
        }

        $merchantRegistrationRequestEntity = $this->getFactory()
            ->createMerchantRegistrationRequestMapper()
            ->mapMerchantRegistrationRequestTransferToMerchantRegistrationRequestEntity(
                $merchantRegistrationRequestTransfer,
                $merchantRegistrationRequestEntity,
            );

        $merchantRegistrationRequestEntity->save();

        return $this->getFactory()
            ->createMerchantRegistrationRequestMapper()
            ->mapMerchantRegistrationRequestEntityToMerchantRegistrationRequestTransfer(
                $merchantRegistrationRequestEntity,
                $merchantRegistrationRequestTransfer,
            );
    }
}
