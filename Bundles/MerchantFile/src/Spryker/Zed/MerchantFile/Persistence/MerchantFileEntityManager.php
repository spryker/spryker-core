<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Persistence;

use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFilePersistenceFactory getFactory()
 */
class MerchantFileEntityManager extends AbstractEntityManager implements MerchantFileEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function saveMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer
    {
        $merchantFileEntity = $this->getFactory()
            ->createMerchantFileQuery()
            ->filterByUuid($merchantFileTransfer->getUuid())
            ->findOneOrCreate();

        $merchantFileEntity = $this->getFactory()
            ->createMerchantFileMapper()
            ->mapTransferToEntity($merchantFileTransfer, $merchantFileEntity);

        $merchantFileEntity->save();

        return $this->getFactory()
            ->createMerchantFileMapper()
            ->mapEntityToTransfer($merchantFileEntity, $merchantFileTransfer);
    }
}
