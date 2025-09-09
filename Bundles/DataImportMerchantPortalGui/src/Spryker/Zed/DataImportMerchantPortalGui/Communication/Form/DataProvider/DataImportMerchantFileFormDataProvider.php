<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataImportMerchantFileForm;
use Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface;
use Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToMerchantUserFacadeInterface;

class DataImportMerchantFileFormDataProvider
{
    /**
     * @param \Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig $dataImportMerchantPortalGuiConfig
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\DataImportMerchantPortalGui\Dependency\Facade\DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface $dataImportMerchantFacade
     */
    public function __construct(
        protected DataImportMerchantPortalGuiConfig $dataImportMerchantPortalGuiConfig,
        protected DataImportMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        protected DataImportMerchantPortalGuiToDataImportMerchantFacadeInterface $dataImportMerchantFacade
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function getData(): DataImportMerchantFileTransfer
    {
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();

        return (new DataImportMerchantFileTransfer())
            ->setIdUser($merchantUserTransfer->getIdUserOrFail())
            ->setMerchantReference($merchantUserTransfer->getMerchantOrFail()->getMerchantReferenceOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(DataImportMerchantFileTransfer $dataImportMerchantFileTransfer): array
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setMerchantReference($dataImportMerchantFileTransfer->getMerchantReferenceOrFail());

        $csvPossibleHeaders = $this->dataImportMerchantFacade->getPossibleCsvHeadersIndexedByImporterType($merchantTransfer);

        return [
            DataImportMerchantFileForm::OPTION_TYPE_CHOICES => array_combine(
                $this->dataImportMerchantPortalGuiConfig->getSupportedImporterTypes(),
                $this->dataImportMerchantPortalGuiConfig->getSupportedImporterTypes(),
            ),
            DataImportMerchantFileForm::OPTION_POSSIBLE_CSV_HEADERS => $csvPossibleHeaders,
        ];
    }
}
