<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductImageFilterTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductConcreteImageSetProductImageStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @uses {@link \Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET}
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_IMAGE_SET = 'spy_product_image_set_to_product_image.fk_product_image_set';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productImageSetIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys(
                $eventEntityTransfers,
                static::COL_FK_PRODUCT_IMAGE_SET,
            );

        $productIds = $this->getFactory()->getProductImageFacade()->getProductConcreteIds(
            (new ProductImageFilterTransfer())->setProductImageSetIds($productImageSetIds),
        );

        $this->getFacade()->unpublishProductConcreteImages($productIds);
    }
}
