<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;

class PriceProductTransferProductDataExpander implements PriceProductTransferDataExpanderInterface
{
    protected const ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND = 'Concrete product was not found by provided sku %s';
    protected const ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND = 'Abstract product was not found by provided sku %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface
     */
    protected $productFacade;

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
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        if ($priceProductTransfer->getSkuProductAbstract()) {
            $productAbstractId = $this->productFacade->findProductAbstractIdBySku(
                $priceProductTransfer->getSkuProductAbstract()
            );

            if ($productAbstractId === null) {
                throw new PriceProductScheduleListImportException(
                    sprintf(
                        static::ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND,
                        $priceProductTransfer->getSkuProductAbstract()
                    )
                );
            }
            $priceProductTransfer->setIdProductAbstract($productAbstractId);
        }

        if ($priceProductTransfer->getSkuProduct()) {
            $productConcreteId = $this->productFacade->findProductConcreteIdBySku(
                $priceProductTransfer->getSkuProduct()
            );

            if ($productConcreteId === null) {
                throw new PriceProductScheduleListImportException(
                    sprintf(
                        static::ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND,
                        $priceProductTransfer->getSkuProduct()
                    )
                );
            }

            $priceProductTransfer->setIdProduct($productConcreteId);
        }

        return $priceProductTransfer;
    }
}
