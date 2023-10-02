<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Communication\Plugin\ServicePointCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig getConfig()
 * @method \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ClickAndCollectExample\Business\ClickAndCollectExampleFacadeInterface getFacade()
 * @method \Spryker\Zed\ClickAndCollectExample\Communication\ClickAndCollectExampleCommunicationFactory getFactory()
 */
class ClickAndCollectExampleDeliveryServicePointQuoteItemReplaceStrategyPlugin extends AbstractPlugin implements ServicePointQuoteItemReplaceStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.store.name` to be set.
     * - Requires `QuoteTransfer.currency.code`to be set.
     * - Requires `QuoteTransfer.priceMode`to be set.
     * - Requires `ItemTransfer.name` for each `QuoteTransfer.item` to be set.
     * - Requires `ItemTransfer.sku` for each `QuoteTransfer.item` to be set.
     * - Requires `ItemTransfer.quantity` for each `QuoteTransfer.item` to be set.
     * - Filters applicable `QuoteTransfer.items` for product offer replacement.
     * - Fetches available replacement product offers from Persistence.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Returns `QuoteResponseTransfer` with modified `QuoteTransfer.items` if replacements take place.
     * - Adds `QuoteErrorTransfer` to `QuoteResponseTransfer.errors` if applicable product offers have not been replaced.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->replaceDeliveryQuoteItemProductOffers($quoteTransfer);
    }
}
