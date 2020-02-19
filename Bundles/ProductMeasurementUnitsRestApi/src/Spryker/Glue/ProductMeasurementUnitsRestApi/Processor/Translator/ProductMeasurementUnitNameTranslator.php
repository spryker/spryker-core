<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface;

class ProductMeasurementUnitNameTranslator implements ProductMeasurementUnitNameTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ProductMeasurementUnitsRestApi\Dependency\Client\ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ProductMeasurementUnitsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[] $productMeasurementUnitTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getProductMeasurementUnitTransfersWithTranslatedNames(
        array $productMeasurementUnitTransfers,
        string $localeName
    ): array {
        $names = [];
        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $names[] = $productMeasurementUnitTransfer->getName();
        }

        $translatedNames = $this->glossaryStorageClient->translateBulk($names, $localeName);
        foreach ($productMeasurementUnitTransfers as $productMeasurementUnitTransfer) {
            $productMeasurementUnitTransfer->setName($translatedNames[$productMeasurementUnitTransfer->getName()]);
        }

        return $productMeasurementUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    public function getProductMeasurementUnitTransferWithTranslatedName(
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer,
        string $localeName
    ): ProductMeasurementUnitTransfer {
        return $productMeasurementUnitTransfer
            ->setName($this->glossaryStorageClient->translate($productMeasurementUnitTransfer->getName(), $localeName));
    }
}
