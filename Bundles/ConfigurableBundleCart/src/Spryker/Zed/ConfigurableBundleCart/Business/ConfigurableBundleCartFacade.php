<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\Business\ConfigurableBundleCartBusinessFactory getFactory()
 * @method \Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartRepositoryInterface getRepository()
 */
class ConfigurableBundleCartFacade extends AbstractFacade implements ConfigurableBundleCartFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateConfiguredBundleQuantityForQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleQuantityUpdater()
            ->updateConfiguredBundleQuantity($quoteTransfer);
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
    public function updateConfiguredBundleQuantityPerSlotForQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleQuantityUpdater()
            ->updateConfiguredBundleQuantityPerSlot($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkConfiguredBundleQuantityInQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->createConfiguredBundleQuantityChecker()
            ->checkConfiguredBundleQuantity($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandConfiguredBundleItemsWithQuantityPerSlot(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleQuantityExpander()
            ->expandConfiguredBundleItemsWithQuantityPerSlot($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandConfiguredBundleItemsWithGroupKey(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleGroupKeyExpander()
            ->expandConfiguredBundleItemsWithGroupKey($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkConfiguredBundleTemplateSlotCombination(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleTemplateSlotChecker()
            ->checkConfiguredBundleTemplateSlotCombination($cartChangeTransfer);
    }
}
