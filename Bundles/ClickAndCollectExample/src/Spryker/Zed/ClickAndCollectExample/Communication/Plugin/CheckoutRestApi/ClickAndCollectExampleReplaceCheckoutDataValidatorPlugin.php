<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig getConfig()
 * @method \Spryker\Zed\ClickAndCollectExample\Business\ClickAndCollectExampleFacadeInterface getFacade()
 * @method \Spryker\Zed\ClickAndCollectExample\Communication\ClickAndCollectExampleCommunicationFactory getFactory()
 */
class ClickAndCollectExampleReplaceCheckoutDataValidatorPlugin extends AbstractPlugin implements CheckoutDataValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CheckoutDataTransfer.quote` to be set.
     * - Collects product offers with service point, shipment, shipment type and shipment method.
     * - Replaces filtered product offers with suitable product offers from Persistence.
     * - Does not modify original quote.
     * - Returns errors in case any of items can not be replaced.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->validateQuoteItemProductOfferReplacement($checkoutDataTransfer);
    }
}
