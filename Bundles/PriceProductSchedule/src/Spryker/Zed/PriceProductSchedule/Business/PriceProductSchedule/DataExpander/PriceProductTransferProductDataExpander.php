<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductExpandResultTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface;

class PriceProductTransferProductDataExpander extends PriceProductTransferAbstractDataExpander
{
    protected const ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND = 'Concrete product was not found by provided sku %s';
    protected const ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND = 'Abstract product was not found by provided sku %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface
     */
    protected $productFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface $productFinder
     */
    public function __construct(
        ProductFinderInterface $productFinder
    ) {
        $this->productFinder = $productFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductExpandResultTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductExpandResultTransfer {
        $priceProductExpandResultTransfer = (new PriceProductExpandResultTransfer())
            ->setIsSuccess(false);

        if ($priceProductTransfer->getSkuProductAbstract()) {
            $productAbstractId = $this->productFinder
                ->findProductAbstractIdBySku($priceProductTransfer->getSkuProductAbstract());

            if ($productAbstractId === null) {
                $priceProductScheduleImportErrorTransfer = $this->createPriceProductScheduleListImportErrorTransfer(
                    sprintf(
                        static::ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND,
                        $priceProductTransfer->getSkuProductAbstract()
                    )
                );

                return $priceProductExpandResultTransfer
                    ->setError($priceProductScheduleImportErrorTransfer);
            }
            $priceProductTransfer->setIdProductAbstract($productAbstractId);
        }

        if ($priceProductTransfer->getSkuProduct()) {
            $productConcreteId = $this->productFinder
                ->findProductConcreteIdBySku($priceProductTransfer->getSkuProduct());

            if ($productConcreteId === null) {
                $priceProductScheduleImportErrorTransfer = $this->createPriceProductScheduleListImportErrorTransfer(
                    sprintf(
                        static::ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND,
                        $priceProductTransfer->getSkuProduct()
                    )
                );

                return $priceProductExpandResultTransfer
                    ->setError($priceProductScheduleImportErrorTransfer);
            }

            $priceProductTransfer->setIdProduct($productConcreteId);
        }

        return $priceProductExpandResultTransfer
            ->setPriceProduct($priceProductTransfer)
            ->setIsSuccess(true);
    }
}
