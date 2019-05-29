<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductExpandResultTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;

abstract class PriceProductTransferAbstractDataExpander implements PriceProductTransferDataExpanderInterface
{
    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer
     */
    protected function createPriceProductScheduleListImportErrorTransfer(
        string $errorMessage
    ): PriceProductScheduleListImportErrorTransfer {
        return (new PriceProductScheduleListImportErrorTransfer())
            ->setMessage($errorMessage);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\PriceProductExpandResultTransfer
     */
    protected function createErrorPriceProductExpandResultTransfer(
        string $errorMessage
    ): PriceProductExpandResultTransfer {
        $priceProductScheduleImportErrorTransfer = $this->createPriceProductScheduleListImportErrorTransfer(
            $errorMessage
        );

        return (new PriceProductExpandResultTransfer())
            ->setIsSuccess(false)
            ->setError($priceProductScheduleImportErrorTransfer);
    }
}
