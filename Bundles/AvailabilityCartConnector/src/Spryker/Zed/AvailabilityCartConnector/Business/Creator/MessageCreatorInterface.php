<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Business\Creator;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\DecimalObject\Decimal;

interface MessageCreatorInterface
{
    /**
     * @param \Spryker\DecimalObject\Decimal $availability
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function createItemIsNotAvailableMessage(Decimal $availability, string $sku): MessageTransfer;
}
