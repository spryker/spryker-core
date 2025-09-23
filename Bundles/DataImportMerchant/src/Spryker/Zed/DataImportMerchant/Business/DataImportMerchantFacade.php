<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantBusinessFactory getFactory()
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface getEntityManager()
 */
class DataImportMerchantFacade extends AbstractFacade implements DataImportMerchantFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function createDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        return $this->getFactory()
            ->createDataImportMerchantFileCreator()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer {
        return $this->getFactory()
            ->createDataImportMerchantFileReader()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function import(): void
    {
        $this->getFactory()->createDataImportMerchantFileImporter()->import();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, array<string>>
     */
    public function getPossibleCsvHeadersIndexedByImporterType(MerchantTransfer $merchantTransfer): array
    {
        return $this->getFactory()
            ->createPossibleCsvHeaderProvider()
            ->getPossibleCsvHeadersIndexedByImporterType($merchantTransfer);
    }
}
