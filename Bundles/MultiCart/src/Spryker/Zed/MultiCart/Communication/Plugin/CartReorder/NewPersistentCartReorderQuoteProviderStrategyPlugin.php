<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCart\Communication\MultiCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiCart\Business\MultiCartBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\MultiCart\MultiCartConfig getConfig()
 */
class NewPersistentCartReorderQuoteProviderStrategyPlugin extends AbstractPlugin implements CartReorderQuoteProviderStrategyPluginInterface
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
    protected const REORDER_STRATEGY_NEW = 'new';

    /**
     * {@inheritDoc}
     * - Checks if `CartReorderRequestTransfer.reorderStrategy` is set to `new`.
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
        if ($cartReorderRequestTransfer->getReorderStrategy() !== static::REORDER_STRATEGY_NEW) {
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
     * - Creates new customer quote.
     * - Returns the created quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer
    {
        return $this->getBusinessFactory()
            ->createCartReorderProvider()
            ->getQuoteForCartReorder($cartReorderRequestTransfer);
    }
}
