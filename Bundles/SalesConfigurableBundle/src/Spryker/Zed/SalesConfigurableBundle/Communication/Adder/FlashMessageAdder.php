<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication\Adder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToMessengerFacadeInterface;

class FlashMessageAdder implements FlashMessageAdderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS = 'sales_configured_bundle.success.items_added_to_cart_as_individual_products';

    /**
     * @var \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToMessengerFacadeInterface
     */
    protected SalesConfigurableBundleToMessengerFacadeInterface $messengerFacade;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(SalesConfigurableBundleToMessengerFacadeInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function addInfoMessage(OrderTransfer $orderTransfer): void
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getSalesOrderConfiguredBundleItem()) {
                continue;
            }

            $messageTransfer = (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_ITEMS_ADDED_TO_CART_SUCCESS);

            $this->messengerFacade->addInfoMessage($messageTransfer);

            return;
        }
    }
}
