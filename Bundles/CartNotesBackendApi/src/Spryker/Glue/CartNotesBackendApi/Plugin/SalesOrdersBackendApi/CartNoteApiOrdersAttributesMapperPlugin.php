<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartNotesBackendApi\Plugin\SalesOrdersBackendApi;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\ApiOrdersAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\CartNotesBackendApi\CartNotesBackendApiFactory getFactory()
 */
class CartNoteApiOrdersAttributesMapperPlugin extends AbstractPlugin implements ApiOrdersAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ApiOrdersAttributes.cartNote` with `Order.cartNote` property.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer>
     */
    public function mapOrderTransfersToApiOrdersAttributesTransfer(
        array $orderTransfers,
        array $apiOrdersAttributesTransfers
    ): array {
        return $this->getFactory()
            ->createCartNotesApiOrdersAttributesMapper()
            ->mapOrderTransfersToApiOrdersAttributesTransfer(
                $orderTransfers,
                $apiOrdersAttributesTransfers,
            );
    }
}
