<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence;

use Generated\Shared\Transfer\MerchantProductTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductPersistenceFactory getFactory()
 */
class MerchantProductEntityManager extends AbstractEntityManager implements MerchantProductEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function create(MerchantProductTransfer $merchantProductTransfer): MerchantProductTransfer
    {
        $merchantProductTransfer->requireIdMerchant()->requireIdProductAbstract();

        $merchantProductMapper = $this->getFactory()->createMerchantProductMapper();

        $merchantProductEntity = $merchantProductMapper->mapMerchantProductTransferToMerchantProductEntity(
            $merchantProductTransfer,
            new SpyMerchantProductAbstract()
        );

        $merchantProductEntity->save();

        return $merchantProductMapper->mapMerchantProductEntityToMerchantProductTransfer(
            $merchantProductEntity,
            $merchantProductTransfer
        );
    }
}
