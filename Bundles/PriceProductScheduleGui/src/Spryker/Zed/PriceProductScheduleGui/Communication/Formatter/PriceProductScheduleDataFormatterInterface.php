<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductScheduleDataFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function formatTitle(PriceProductTransfer $priceProductTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function formatRedirectUrl(PriceProductTransfer $priceProductTransfer): string;

    /**
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatTimezoneText(?string $timezone): string;
}
