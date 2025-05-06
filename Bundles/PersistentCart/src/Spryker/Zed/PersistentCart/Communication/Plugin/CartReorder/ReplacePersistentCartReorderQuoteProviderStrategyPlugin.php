<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 * @method \Spryker\Zed\PersistentCart\Communication\PersistentCartCommunicationFactory getFactory()
 */
class ReplacePersistentCartReorderQuoteProviderStrategyPlugin extends AbstractPlugin implements CartReorderQuoteProviderStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     *
     * @var string
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var string
     */
    protected const REORDER_STRATEGY_REPLACE = 'replace';

    /**
     * {@inheritDoc}
     * - Checks if `CartReorderRequestTransfer.reorderStrategy` is set and equals to `replace`.
     * - Checks if the storage strategy is database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CartReorderRequestTransfer $cartReorderRequestTransfer): bool
    {
        if (
            $cartReorderRequestTransfer->getReorderStrategy()
            && $cartReorderRequestTransfer->getReorderStrategy() !== static::REORDER_STRATEGY_REPLACE
        ) {
            return false;
        }

        $storageStrategy = $this->getFactory()
            ->getQuoteFacade()
            ->getStorageStrategy();

        return $storageStrategy === static::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * {@inheritDoc}
     * - Requires `CartReorderRequestTransfer.customerReference` to be set.
     * - Finds customer quote by `CartReorderRequestTransfer.customerReference`.
     * - Creates quote if it's not exists.
     * - Removes items from the found quote.
     * - Returns the found quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer
    {
        return $this->getFacade()->getQuoteForCartReorder($cartReorderRequestTransfer);
    }
}
