<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteBulkProductDiscontinuedPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface getRepository()
 */
class ProductLabelRelationPostDeleteBulkProductDiscontinuedPlugin extends AbstractPlugin implements PostDeleteBulkProductDiscontinuedPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes ProductAbstract relations for label.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    public function execute(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): void
    {
        $productConcreteIds = $this->getProductConcreteIds($productDiscontinuedCollectionTransfer);

        $this->getFacade()->removeProductAbstractRelationsForLabelInBulk($productConcreteIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return int[]
     */
    protected function getProductConcreteIds(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): array
    {
        $productConcreteIds = [];

        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $productConcreteIds[] = $productDiscontinuedTransfer->getFkProduct();
        }

        return $productConcreteIds;
    }
}
