<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Communication\Plugin\ServicePointCart;

use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig getConfig()
 * @method \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ClickAndCollectExample\Business\ClickAndCollectExampleFacadeInterface getFacade()
 * @method \Spryker\Zed\ClickAndCollectExample\Communication\ClickAndCollectExampleCommunicationFactory getFactory()
 */
class ClickAndCollectExampleServicePointQuoteItemReplaceStrategyPlugin extends AbstractPlugin implements ServicePointQuoteItemReplaceStrategyPluginInterface
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
     * - Requires `ItemTransfer.servicePoint.IdServicePoint` for each `QuoteTransfer.item` to be set in case pickup shipment type.
     * - Filters applicable `QuoteTransfer.items` for product offer replacement.
     * - Merges filtered items quantity by sku and merchant reference.
     * - Fetches available replacement product offers from Persistence.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Returns `QuoteReplacementResponseTransfer` with modified `QuoteTransfer.items` if replacements take place.
     * - Adds `QuoteErrorTransfer` to `QuoteReplacementResponseTransfer.errors` if applicable product offers have not been replaced.
     * - Adds QuoteTransfer.item.groupKey to QuoteReplacementResponseTransfer.failedItemGroupKeys if the product offer for the applicable item was not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer
    {
        return $this->getFacade()->replaceQuoteItemProductOffers($quoteTransfer);
    }
}
