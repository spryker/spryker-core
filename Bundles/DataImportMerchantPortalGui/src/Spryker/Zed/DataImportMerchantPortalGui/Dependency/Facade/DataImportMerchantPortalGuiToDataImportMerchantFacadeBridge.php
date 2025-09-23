<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

class DataImportMerchantPortalGuiToDataImportMerchantFacadeBridge implements DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface
{
    /**
     * @var \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantFacadeInterface
     */
    protected $dataImportMerchantFacade;

    /**
     * @param \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantFacadeInterface $dataImportMerchantFacade
     */
    public function __construct($dataImportMerchantFacade)
    {
        $this->dataImportMerchantFacade = $dataImportMerchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer {
        return $this->dataImportMerchantFacade->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function createDataImportMerchantFileCollection(
        DataImportMerchantFileCollectionRequestTransfer $dataImportMerchantFileCollectionRequestTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        return $this->dataImportMerchantFacade->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    public function getPossibleCsvHeadersIndexedByImporterType(MerchantTransfer $merchantTransfer): array
    {
        return $this->dataImportMerchantFacade->getPossibleCsvHeadersIndexedByImporterType($merchantTransfer);
    }
}
