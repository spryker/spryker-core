<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Persistence;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantPersistenceFactory getFactory()
 */
class DataImportMerchantEntityManager extends AbstractEntityManager implements DataImportMerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function saveDataImportMerchantFile(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): DataImportMerchantFileTransfer
    {
        $dataImportMerchantFileEntity = $this->getFactory()
            ->createDataImportMerchantFileQuery()
            ->filterByIdDataImportMerchantFile($dataImportMerchantFileTransfer->getIdDataImportMerchantFile())
            ->findOneOrCreate();

        $dataImportMerchantFileEntity = $this->getFactory()
            ->createDataImportMerchantFileMapper()
            ->mapDataImportMerchantFileTransferToDataImportMerchantFileEntity($dataImportMerchantFileTransfer, $dataImportMerchantFileEntity);

        $dataImportMerchantFileEntity->save();

        return $this->getFactory()
            ->createDataImportMerchantFileMapper()
            ->mapDataImportMerchantFileEntityToDataImportMerchantFileTransfer($dataImportMerchantFileEntity, $dataImportMerchantFileTransfer);
    }
}
