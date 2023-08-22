<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartNotesBackendApi\Plugin\SalesOrdersBackendApi;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\OrdersBackendApiAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\CartNotesBackendApi\CartNotesBackendApiFactory getFactory()
 */
class CartNoteOrdersBackendApiAttributesMapperPlugin extends AbstractPlugin implements OrdersBackendApiAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `OrdersBackendApiAttributes.cartNote` with `Order.cartNote` property.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    public function mapOrderTransfersToOrdersBackendApiAttributesTransfers(
        array $orderTransfers,
        array $ordersBackendApiAttributesTransfers
    ): array {
        return $this->getFactory()
            ->createCartNotesOrdersBackendApiAttributesMapper()
            ->mapOrderTransfersToOrdersBackendApiAttributesTransfers(
                $orderTransfers,
                $ordersBackendApiAttributesTransfers,
            );
    }
}
