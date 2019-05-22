<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;

class PriceProductTransferProductDataExpander implements PriceProductTransferDataExpanderInterface
{
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
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductTransfer {
        if ($priceProductScheduleImportTransfer->getSkuProductAbstract()) {
            $productAbstractId = $this->productFacade->findProductAbstractIdBySku(
                $priceProductScheduleImportTransfer->getSkuProductAbstract()
            );

            if ($productAbstractId === null) {
                throw new PriceProductScheduleListImportException(
                    sprintf(
                        'Abstract product was not found by provided sku "%s"',
                        $priceProductScheduleImportTransfer->getSkuProductAbstract()
                    )
                );
            }
            $priceProductTransfer->setIdProductAbstract($productAbstractId);
            $priceProductTransfer->setSkuProductAbstract($priceProductScheduleImportTransfer->getSkuProductAbstract());
        }

        if ($priceProductScheduleImportTransfer->getSkuProduct()) {
            $productConcreteId = $this->productFacade->findProductConcreteIdBySku(
                $priceProductScheduleImportTransfer->getSkuProduct()
            );

            if ($productConcreteId === null) {
                throw new PriceProductScheduleListImportException(
                    sprintf(
                        'Concrete product was not found by provided sku "%s"',
                        $priceProductScheduleImportTransfer->getSkuProduct()
                    )
                );
            }

            $priceProductTransfer->setIdProduct($productConcreteId);
            $priceProductTransfer->setSkuProduct($priceProductScheduleImportTransfer->getSkuProduct());
        }

        return $priceProductTransfer;
    }
}
