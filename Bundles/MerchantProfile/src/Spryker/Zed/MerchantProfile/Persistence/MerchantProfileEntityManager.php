<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;
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
    public function create(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileTransferToMerchantProfileEntity($merchantProfileTransfer, new SpyMerchantProfile());

        $merchantProfileEntity->save();

        $merchantProfileTransfer = $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantProfileEntity, $merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function update(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->getFactory()
            ->createMerchantProfileQuery()
            ->filterByIdMerchantProfile($merchantProfileTransfer->getIdMerchantProfile())
            ->findOne();

        $merchantProfileEntity = $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileTransferToMerchantProfileEntity($merchantProfileTransfer, $merchantProfileEntity);

        $merchantProfileEntity->save();

        $merchantProfileTransfer = $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileEntityToMerchantProfileTransfer($merchantProfileEntity, $merchantProfileTransfer);

        return $merchantProfileTransfer;
    }
}
