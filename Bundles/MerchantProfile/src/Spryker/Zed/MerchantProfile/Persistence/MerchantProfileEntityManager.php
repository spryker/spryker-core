<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfilePersistenceFactory getFactory()
 */
class MerchantProfileEntityManager extends AbstractEntityManager implements MerchantProfileEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->getFactory()
            ->createMerchantProfileQuery()
            ->filterByIdMerchantProfile($merchantProfileTransfer->getIdMerchantProfile())
            ->findOneOrCreate();

        $merchantProfileEntity = $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileTransferToMerchantProfileEntity($merchantProfileTransfer, $merchantProfileEntity);

        $merchantProfileEntity->save();

        $merchantProfileTransfer->setIdMerchantProfile($merchantProfileEntity->getIdMerchantProfile());

        return $merchantProfileTransfer;
    }
}
