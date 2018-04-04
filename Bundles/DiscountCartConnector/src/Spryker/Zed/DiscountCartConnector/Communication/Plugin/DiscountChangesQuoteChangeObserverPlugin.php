<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountCartConnector\Business\DiscountCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountCartConnector\DiscountCartConnectorConfig getConfig()
 * @method \Spryker\Zed\DiscountCartConnector\Communication\DiscountCartConnectorCommunicationFactory getFactory()
 */
class DiscountChangesQuoteChangeObserverPlugin extends AbstractPlugin implements QuoteChangeObserverPluginInterface
{
    /**
     * Specification:
     * - Checks cart changes on cart validate
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $this->getFacade()->checkDiscountChanges($resultQuoteTransfer, $sourceQuoteTransfer);
    }
}
