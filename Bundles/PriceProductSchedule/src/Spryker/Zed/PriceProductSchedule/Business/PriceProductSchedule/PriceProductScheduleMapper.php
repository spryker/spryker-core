<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductScheduleMapper implements PriceProductScheduleMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander\PriceProductTransferDataExpanderInterface[]
     */
    protected $dataExpanderList;

    /**
     * @param array $dataExpanderList
     */
    public function __construct(
        array $dataExpanderList
    ) {
        $this->dataExpanderList = $dataExpanderList;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $this->mapPriceProductScheduleImportTransferToPriceProductTransfer(
            $priceProductScheduleImportTransfer,
            new PriceProductTransfer()
        );

        return $priceProductScheduleTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setPriceProduct($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductScheduleImportTransferToPriceProductTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceProductTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true);

        foreach ($this->dataExpanderList as $dataExpander) {
            $priceProductTransfer = $dataExpander->expand($priceProductTransfer, $priceProductScheduleImportTransfer);
        }

        return $priceProductTransfer;
    }
}
