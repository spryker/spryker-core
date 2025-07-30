<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence;

use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiPersistenceFactory getFactory()
 */
class FileImportMerchantPortalGuiEntityManager extends AbstractEntityManager implements FileImportMerchantPortalGuiEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function saveMerchantFileImport(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportTransfer {
        $merchantFileImportEntity = $this->getFactory()
            ->createMerchantFileImportQuery()
            ->filterByIdMerchantFileImport($merchantFileImportTransfer->getIdMerchantFileImport())
            ->findOneOrCreate();

        $merchantFileImportEntity = $this->getFactory()
            ->createMerchantFileImportMapper()
            ->mapTransferToEntity($merchantFileImportTransfer, $merchantFileImportEntity);

        $merchantFileImportEntity->save();

        return $this->getFactory()
            ->createMerchantFileImportMapper()
            ->mapEntityToTransfer($merchantFileImportEntity, $merchantFileImportTransfer);
    }
}
