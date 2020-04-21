<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductLabelSearch\Business\ProductLabelSearchBusinessFactory getFactory()
 */
class ProductLabelSearchFacade extends AbstractFacade implements ProductLabelSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelEvents(array $eventTransfers): void
    {
        $this->getFactory()->createProductLabelSearchWriter()->writeCollectionByProductLabelEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelProductAbstractEvents(array $eventTransfers): void
    {
        $this->getFactory()->createProductLabelSearchWriter()->writeCollectionByProductLabelProductAbstractEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageData(ProductPageLoadTransfer $loadTransfer)
    {
        return $this->getFactory()
            ->createProductPageDataTransferExpander()
            ->expandProductPageDataTransfer($loadTransfer);
    }
}
