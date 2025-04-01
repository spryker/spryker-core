<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementBusinessFactory getBusinessFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 */
class SspShipmentTypeQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands items with the default shipment type if no shipment type is set.
     * - Expands bundle items with the default shipment type if no shipment type is set.
     * - Retrieves the default shipment type with key {@link \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig::getDefaultShipmentType()}.
     * - Sets the retrieved shipment type to the item if found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getBusinessFactory()
            ->createShipmentTypeItemExpander()
            ->expandQuoteItemsWithShipmentType($quoteTransfer);
    }
}
