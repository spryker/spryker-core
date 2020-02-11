<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiBusinessFactory getFactory()
 * @method \Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiEntityManagerInterface getEntityManager()
 */
class SharedCartsRestApiFacade extends AbstractFacade implements SharedCartsRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        return $this->getFactory()
            ->createSharedCartReader()
            ->getSharedCartsByCartUuid($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function create(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        return $this->getFactory()
            ->createSharedCartCreator()
            ->create($shareCartRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function update(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        return $this->getFactory()
            ->createSharedCartUpdater()
            ->update($shareCartRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function delete(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        return $this->getFactory()
            ->createSharedCartDeleter()
            ->delete($shareCartRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithQuotePermissionGroup(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createQuotePermissionGroupReader()
            ->expandQuoteWithQuotePermissionGroup($quoteTransfer);
    }
}
