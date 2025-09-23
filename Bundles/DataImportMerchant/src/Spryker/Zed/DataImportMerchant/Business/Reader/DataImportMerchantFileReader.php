<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Reader;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface;

class DataImportMerchantFileReader implements DataImportMerchantFileReaderInterface
{
    /**
     * @param \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface $dataImportMerchantRepository
     * @param list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface> $dataImportMerchantFileExpanderPlugins
     */
    public function __construct(
        protected DataImportMerchantRepositoryInterface $dataImportMerchantRepository,
        protected array $dataImportMerchantFileExpanderPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function getDataImportMerchantFileCollection(
        DataImportMerchantFileCriteriaTransfer $dataImportMerchantFileCriteriaTransfer
    ): DataImportMerchantFileCollectionTransfer {
        $dataImportMerchantFileCollectionTransfer = $this->dataImportMerchantRepository
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        return $this->executeDataImportMerchantFileExpanderPlugins($dataImportMerchantFileCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    protected function executeDataImportMerchantFileExpanderPlugins(
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): DataImportMerchantFileCollectionTransfer {
        foreach ($this->dataImportMerchantFileExpanderPlugins as $dataImportMerchantFileExpanderPlugin) {
            $dataImportMerchantFileCollectionTransfer = $dataImportMerchantFileExpanderPlugin->expand($dataImportMerchantFileCollectionTransfer);
        }

        return $dataImportMerchantFileCollectionTransfer;
    }
}
