<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface getRepository()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class MarkAsDefaultQuoteAfterSavePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * Specification:
     * - Mark shared quote as default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (strcmp($quoteTransfer->getCustomer()->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0) {
            return $quoteTransfer;
        }

        return $quoteTransfer->setIsDefault(
            $this->getFacade()->isSharedQuoteDefault(
                $quoteTransfer->getIdQuote(),
                $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()
            )
        );
    }
}
