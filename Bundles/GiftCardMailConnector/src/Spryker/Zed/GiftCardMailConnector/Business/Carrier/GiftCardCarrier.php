<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business\Carrier;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Mail\GiftCardDeliveryMailTypePlugin;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeInterface;

class GiftCardCarrier implements GiftCardCarrierInterface
{
    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface
     */
    protected $giftCardFacade;

    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface $giftCardFacade
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        GiftCardMailConnectorToMailFacadeInterface $mailFacade,
        GiftCardMailConnectorToCustomerFacadeInterface $customerFacade,
        GiftCardMailConnectorToGiftCardFacadeInterface $giftCardFacade,
        GiftCardMailConnectorToSalesFacadeInterface $salesFacade
    ) {
        $this->mailFacade = $mailFacade;
        $this->customerFacade = $customerFacade;
        $this->giftCardFacade = $giftCardFacade;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function deliverByIdSalesOrderItem($idSalesOrderItem)
    {
        $mailTransfer = $this->prepareMailTransfer($idSalesOrderItem, new MailTransfer());

        $mailTransfer
            ->requireCustomer()
            ->requireGiftCards();

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function prepareMailTransfer($idSalesOrderItem, $mailTransfer)
    {
        $giftCardTransfer = $this->giftCardFacade->findGiftCardByIdSalesOrderItem($idSalesOrderItem);

        if ($giftCardTransfer === null) {
            return $mailTransfer;
        }

        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrderItem($idSalesOrderItem);

        if ($orderTransfer === null) {
            return $mailTransfer;
        }

        $customerTransfer = $this->getCustomerTransfer($orderTransfer);

        $mailTransfer = $mailTransfer
            ->setType(GiftCardDeliveryMailTypePlugin::MAIL_TYPE)
            ->setCustomer($customerTransfer)
            ->addGiftCard($giftCardTransfer);

        return $mailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer($orderTransfer)
    {
        $customerTransfer = $this->customerFacade->findByReference($orderTransfer->getCustomerReference());

        if ($customerTransfer) {
            return $customerTransfer;
        }

        return (new CustomerTransfer())
            ->setEmail($orderTransfer->getEmail())
            ->setLastName($orderTransfer->getLastName())
            ->setFirstName($orderTransfer->getFirstName());
    }
}
