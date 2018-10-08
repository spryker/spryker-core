<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\ProductConcreteExpander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToGlossaryStorageClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToLocaleClientInterface;
use Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementBaseUnitReaderInterface;

class ProductConcreteExpander implements ProductConcreteExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementBaseUnitReaderInterface
     */
    protected $productMeasurementBaseUnitReader;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementBaseUnitReaderInterface $productMeasurementBaseUnitReader
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Dependency\Client\ProductMeasurementUnitStorageToLocaleClientInterface $localeClient
     */
    public function __construct(
        ProductMeasurementBaseUnitReaderInterface $productMeasurementBaseUnitReader,
        ProductMeasurementUnitStorageToGlossaryStorageClientInterface $glossaryStorageClient,
        ProductMeasurementUnitStorageToLocaleClientInterface $localeClient
    ) {
        $this->productMeasurementBaseUnitReader = $productMeasurementBaseUnitReader;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransferWithMeasurementBaseUnit(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productMeasurementUnitTransfer = $this->findProductMeasurementBaseUnitByIdProduct($productConcreteTransfer);

        if ($productMeasurementUnitTransfer === null) {
            return $productConcreteTransfer;
        }

        $productMeasurementUnitTransfer = $this->translateProductMeasurementBaseUnitname($productMeasurementUnitTransfer);

        return $productConcreteTransfer->setBaseMeasurementUnit($productMeasurementUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    protected function findProductMeasurementBaseUnitByIdProduct(ProductConcreteTransfer $productConcreteTransfer): ?ProductMeasurementUnitTransfer
    {
        return $this->productMeasurementBaseUnitReader->findProductMeasurementBaseUnitByIdProduct(
            $productConcreteTransfer->getIdProductConcrete()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return $this
     */
    protected function translateProductMeasurementBaseUnitname(ProductMeasurementUnitTransfer $productMeasurementUnitTransfer): ProductMeasurementUnitTransfer
    {
        $translatedName = $this->glossaryStorageClient->translate(
            $productMeasurementUnitTransfer->getName(),
            $this->localeClient->getCurrentLocale()
        );

        return $productMeasurementUnitTransfer->setName($translatedName);
    }
}
