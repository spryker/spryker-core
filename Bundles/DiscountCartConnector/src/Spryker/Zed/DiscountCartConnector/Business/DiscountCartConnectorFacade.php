<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCartConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DiscountCartConnector\Business\DiscountCartConnectorBusinessFactory getFactory()
 */
class DiscountCartConnectorFacade extends AbstractFacade implements DiscountCartConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkDiscountChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $this->getFactory()->createQuoteChangeObserver()->checkDiscountChanges($resultQuoteTransfer, $sourceQuoteTransfer);
    }
}
