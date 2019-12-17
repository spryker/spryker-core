<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface getRepository()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class ActiveSharedCartChangedQuoteBeforeSavePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    protected const GLOSSARY_KEY_SHARED_CART_SET_DEFAULT_SUCCESS = 'shared_cart.cart.set_default.success';

    /**
     * {@inheritDoc}
     * - Adds info message in case active shared cart was changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getCustomer()->getCustomerReference() === $quoteTransfer->getCustomerReference()) {
            return $quoteTransfer;
        }

        $isSharedQuoteDefault = $this->getRepository()->isSharedQuoteDefault(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()
        );

        if ($isSharedQuoteDefault) {
            return $quoteTransfer;
        }

        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_SHARED_CART_SET_DEFAULT_SUCCESS)
            ->setParameters(['%quote%' => $quoteTransfer->getName()]);

        $this->getFactory()->getMessengerFacade()->addInfoMessage($messageTransfer);

        return $quoteTransfer;
    }
}
