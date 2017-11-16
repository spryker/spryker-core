<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Mail\GiftCardUsageMailTypePlugin;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface;

class GiftCardUsageMailer implements GiftCardUsageMailerInterface
{
    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface
     */
    protected $giftCardQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailInterface $mailFacade
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface $giftCardQueryContainer
     */
    public function __construct(GiftCardMailConnectorToMailInterface $mailFacade, GiftCardMailConnectorToGiftCardQueryContainerInterface $giftCardQueryContainer)
    {
        $this->mailFacade = $mailFacade;
        $this->giftCardQueryContainer = $giftCardQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function sendUsageNotification(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $quoteTransfer->requireCustomer();
        $quoteTransfer->getCustomer()->requireIdCustomer();

        $mailTransfer = new MailTransfer();
        $mailTransfer = $this->prepareMailTransfer($mailTransfer, $quoteTransfer);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function prepareMailTransfer($mailTransfer, $quoteTransfer)
    {
        $mailTransfer->setType(GiftCardUsageMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setCustomer($quoteTransfer->getCustomer());

        foreach ($quoteTransfer->getGiftCards() as $giftCardTransfer) {
            $mailTransfer->addGiftCard($giftCardTransfer);
        }

        return $mailTransfer;
    }
}
