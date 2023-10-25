<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader;

use Generated\Shared\Transfer\QuoteTransfer;

interface ShipmentTypeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getApplicableShipmentTypeTransfersIndexedByIdShipmentMethod(QuoteTransfer $quoteTransfer): array;
}
