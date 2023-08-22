<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartNotesBackendApi\Processor\Mapper;

interface CartNotesOrdersBackendApiAttributesMapperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    public function mapOrderTransfersToOrdersBackendApiAttributesTransfers(
        array $orderTransfers,
        array $ordersBackendApiAttributesTransfers
    ): array;
}
