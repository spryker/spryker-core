<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\MultiCart\MultiCartConfig getConfig()
 * @method \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCart\Communication\MultiCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface getRepository()
 */
class QuoteMarkedAsDefaultQuoteUpdateBeforePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    protected const GLOSSARY_KEY_MULTI_CART_SET_DEFAULT_SUCCESS = 'multi_cart.cart.set_default.success';
    protected const GLOSSARY_KEY_PARAMETER_QUOTE = '%quote%';

    /**
     * {@inheritDoc}
     * - Adds info message quote was marked as default.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getCustomer()->getCustomerReference() !== $quoteTransfer->getCustomerReference()) {
            return $quoteTransfer;
        }

        if ($this->getFacade()->isQuoteDefault($quoteTransfer->getIdQuote())) {
            return $quoteTransfer;
        }

        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_MULTI_CART_SET_DEFAULT_SUCCESS)
            ->setParameters([static::GLOSSARY_KEY_PARAMETER_QUOTE => $quoteTransfer->getName()]);

        $this->getFactory()->getMessengerFacade()->addInfoMessage($messageTransfer);

        return $quoteTransfer;
    }
}
