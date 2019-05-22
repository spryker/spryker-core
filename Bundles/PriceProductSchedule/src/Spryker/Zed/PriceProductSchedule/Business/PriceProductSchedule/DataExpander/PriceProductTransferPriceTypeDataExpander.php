<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;

class PriceProductTransferPriceTypeDataExpander implements PriceProductTransferDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
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
        $priceTypeTransfer = $this->priceProductFacade->findPriceTypeByName(
            $priceProductScheduleImportTransfer->getPriceTypeName()
        );

        if ($priceTypeTransfer === null) {
            throw new PriceProductScheduleListImportException(
                sprintf(
                    'Price type was not found by provided sku %s',
                    $priceProductScheduleImportTransfer->getPriceTypeName()
                )
            );
        }

        return $priceProductTransfer
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer);
    }
}
