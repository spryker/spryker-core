<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductExpandResultTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;

class PriceProductTransferProductDataExpander extends PriceProductTransferAbstractDataExpander
{
    protected const ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND = 'Concrete product was not found by provided sku %s';
    protected const ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND = 'Abstract product was not found by provided sku %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var array
     */
    protected $productAbstractCache = [];

    /**
     * @var array
     */
    protected $productConcreteCache = [];

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface $productFacade
     */
    public function __construct(
        PriceProductScheduleToProductFacadeInterface $productFacade
    ) {
        $this->productFacade = $productFacade;
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
            $productAbstractId = $this->findProductAbstractIdBySku($priceProductTransfer->getSkuProductAbstract());

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
            $productConcreteId = $this->findProductConcreteIdBySku($priceProductTransfer->getSkuProduct());

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

    /**
     * @param string $sku
     *
     * @return int|null
     */
    protected function findProductAbstractIdBySku(string $sku): ?int
    {
        if (isset($this->productAbstractCache[$sku])) {
            return $this->productAbstractCache[$sku];
        }

        $productAbstractId = $this->productFacade->findProductAbstractIdBySku($sku);

        if ($productAbstractId === null) {
            return null;
        }

        $this->productAbstractCache[$sku] = $productAbstractId;

        return $this->productAbstractCache[$sku];
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    protected function findProductConcreteIdBySku(string $sku): ?int
    {
        if (isset($this->productConcreteCache[$sku])) {
            return $this->productConcreteCache[$sku];
        }

        $productConcreteId = $this->productFacade->findProductConcreteIdBySku($sku);

        if ($productConcreteId === null) {
            return null;
        }

        $this->productConcreteCache[$sku] = $productConcreteId;

        return $this->productConcreteCache[$sku];
    }
}
