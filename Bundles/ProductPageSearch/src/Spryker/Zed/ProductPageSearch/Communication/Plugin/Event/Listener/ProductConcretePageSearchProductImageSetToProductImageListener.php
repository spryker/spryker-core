<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductImageFilterTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductImageSetToProductImageListener extends AbstractProductConcretePageSearchListener
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $productImageSetIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET);

        $productConcreteIds = $this->getFactory()
            ->getProductImageFacade()
            ->getProductConcreteIds((new ProductImageFilterTransfer())->setProductImageSetIds($productImageSetIds));

        $this->publish($productConcreteIds);
    }
}
