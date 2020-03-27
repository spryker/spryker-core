<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageBusinessFactory getFactory()
 */
class ProductLabelStorageFacade extends AbstractFacade implements ProductLabelStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function publishLabelDictionary()
    {
        $this->getFactory()->createProductLabelDictionaryStorageWriter()->publish();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelEvents(array $eventTransfers): void
    {
        $this->getFactory()->createProductLabelDictionaryStorageWriter()
            ->writeProductLabelDictionaryStorageCollectionByProductLabelEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function unpublishLabelDictionary()
    {
        $this->getFactory()->createProductLabelDictionaryStorageWriter()->unpublish();
    }

    /**
     * @param array $eventTransfers
     *
     * @api
     *
     * @return void
     */
    public function deleteCollectionByProductLabelEvents(array $eventTransfers): void
    {
        $this->getFactory();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductLabelStorageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductLabelStorageWriter()->unpublish($productAbstractIds);
    }
}
