<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;

/**
 * @method \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 * @method \Spryker\Zed\OrderCustomReference\Business\OrderCustomReferenceFacadeInterface getFacade()
 */
class UpdateOrderCustomReferenceOrderPostSavePlugin extends AbstractPlugin implements OrderPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates order's custom order reference with the one provided in `QuoteTransfer.orderCustomReference`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $this->getFacade()->saveOrderCustomReferenceFromQuote($quoteTransfer, $saveOrderTransfer, true);

        return $saveOrderTransfer;
    }
}
