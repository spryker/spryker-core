<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Communication\Plugin\PersistentCart;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartOperationAddAfterPluginInterface;

/**
 * @method \Spryker\Zed\MultiCart\MultiCartConfig getConfig()
 * @method \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCart\Communication\MultiCartCommunicationFactory getFactory()
 */
class ActiveCartChangedCartOperationAddAfterPlugin extends AbstractPlugin implements CartOperationAddAfterPluginInterface
{
    protected const GLOSSARY_KEY_MULTI_CART_SET_DEFAULT_SUCCESS = 'multi_cart.cart.set_default.success';
    protected const GLOSSARY_KEY_MULTI_CART_ADD_ITEM_SUCCESS = 'multi_cart.cart.add_item.success';

    /**
     * {@inheritDoc}
     * - Adds success message in case active cart was changed.
     * - Adds success messages that items were added to new active cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    public function execute(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): void {
        if (!$persistentCartChangeTransfer->getIsActiveCartChanged()) {
            return;
        }

        $this->addSuccessMessage(
            static::GLOSSARY_KEY_MULTI_CART_SET_DEFAULT_SUCCESS,
            ['%quote%' => $quoteResponseTransfer->getQuoteTransfer()->getName()]
        );

        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $this->addSuccessMessage(
                static::GLOSSARY_KEY_MULTI_CART_ADD_ITEM_SUCCESS,
                [
                    '%quote%' => $quoteResponseTransfer->getQuoteTransfer()->getName(),
                    '%item%' => $itemTransfer->getSku(),
                ]
            );
        }
    }

    /**
     * @param string $value
     * @param string[] $params
     *
     * @return void
     */
    protected function addSuccessMessage(string $value, array $params = []): void
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($value)
            ->setParameters($params);

        $this->getFactory()->getMessengerFacade()->addSuccessMessage($messageTransfer);
    }
}
