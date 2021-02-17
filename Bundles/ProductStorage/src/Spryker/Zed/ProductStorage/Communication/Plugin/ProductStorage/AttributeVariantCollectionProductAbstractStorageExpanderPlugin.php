<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\ProductStorage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 */
class AttributeVariantCollectionProductAbstractStorageExpanderPlugin extends AbstractPlugin implements ProductAbstractStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the provided ProductAbstractStorage transfer object.
     * - Expects `ProductAbstractStorage.attributeMap.productConcreteIds` to be provided.
     * - Populates `ProductAbstractStorage.attributeMap.attributeVariantCollection` using provided product concretes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expand(ProductAbstractStorageTransfer $productAbstractStorageTransfer): ProductAbstractStorageTransfer
    {
        return $this->getFacade()->expandWithAttributeVariantCollection($productAbstractStorageTransfer);
    }
}
