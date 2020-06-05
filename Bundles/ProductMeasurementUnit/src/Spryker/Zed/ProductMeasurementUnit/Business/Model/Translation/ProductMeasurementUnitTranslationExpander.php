<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface;

class ProductMeasurementUnitTranslationExpander implements ProductMeasurementUnitTranslationExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ProductMeasurementUnitToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $productMeasurementSalesUnitTransfers
     *
     * @return array
     */
    public function translateProductMeasurementSalesUnits(array $productMeasurementSalesUnitTransfers): array
    {
        foreach ($productMeasurementSalesUnitTransfers as $productMeasurementSalesUnitTransfer) {
            $this->translateProductMeasurementSalesUnit($productMeasurementSalesUnitTransfer);
        }

        return $productMeasurementSalesUnitTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function translateProductMeasurementSalesUnit(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $productMeasurementSalesUnitTransfer
            ->requireProductMeasurementUnit();

        $productMeasurementSalesUnitTransfer
            ->getProductMeasurementBaseUnit()
            ->requireProductMeasurementUnit();

        $this->expandSalesUnit($productMeasurementSalesUnitTransfer);
        $this->expandBaseUnit($productMeasurementSalesUnitTransfer);

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return void
     */
    protected function expandSalesUnit(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): void
    {
        $quantityMeasurementUnitName = $productMeasurementSalesUnitTransfer
            ->getProductMeasurementUnit()
            ->getName();

        $localizedQuantityMeasurementUnitName = $this
            ->translate($quantityMeasurementUnitName);

        $productMeasurementSalesUnitTransfer
            ->getProductMeasurementUnit()
            ->setName($localizedQuantityMeasurementUnitName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return void
     */
    protected function expandBaseUnit(ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer): void
    {
        $quantityBaseMeasurementUnitName = $productMeasurementSalesUnitTransfer
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $localizedQuantityBaseMeasurementUnitName = $this
            ->translate($quantityBaseMeasurementUnitName);

        $productMeasurementSalesUnitTransfer
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->setName($localizedQuantityBaseMeasurementUnitName);
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    protected function translate(string $msg): string
    {
        try {
            $localizedMsg = $this->glossaryFacade->translate($msg);
        } catch (MissingTranslationException $e) {
            $localizedMsg = $msg;
        }

        return $localizedMsg;
    }
}
