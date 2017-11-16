<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business\Carrier;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\GiftCard\Business\Exception\GiftCardSalesMetadataNotFoundException;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Mail\GiftCardDeliveryMailTypePlugin;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface;

class GiftCardCarrier implements GiftCardCarrierInterface
{
    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface
     */
    protected $giftCardQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface $giftCardQueryContainer
     */
    public function __construct(
        GiftCardMailConnectorToMailFacadeInterface $mailFacade,
        GiftCardMailConnectorToCustomerFacadeInterface $customerFacade,
        GiftCardMailConnectorToGiftCardQueryContainerInterface $giftCardQueryContainer
    ) {
        $this->mailFacade = $mailFacade;
        $this->customerFacade = $customerFacade;
        $this->giftCardQueryContainer = $giftCardQueryContainer;
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
        $salesOrderItemGiftCard = $this->getGiftCardEntity($idSalesOrderItem);
        $giftCardTransfer = $this->mapSalesOrderItemGiftCardToGiftCardTransfer(
            $salesOrderItemGiftCard,
            new GiftCardTransfer()
        );

        $customerTransfer = $this->getCustomerTransfer(
            $salesOrderItemGiftCard->getSpySalesOrderItem()->getOrder()->getCustomerReference()
        );

        $mailTransfer = $mailTransfer
            ->setType(GiftCardDeliveryMailTypePlugin::MAIL_TYPE)
            ->setCustomer($customerTransfer)
            ->addGiftCard($giftCardTransfer);

        return $mailTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomerTransfer($customerReference)
    {
        $customerTransfer = $this->customerFacade->findByReference($customerReference);

        if (!$customerTransfer) {
            throw new CustomerNotFoundException(
                sprintf('Customer with reference %d is missing', $customerReference)
            );
        }

        return $customerTransfer;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\GiftCard\Business\Exception\GiftCardSalesMetadataNotFoundException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard
     */
    protected function getGiftCardEntity($idSalesOrderItem)
    {
        $salesOrderItemGiftCard = $this->giftCardQueryContainer
            ->queryGiftCardOrderItemMetadata($idSalesOrderItem)
            ->findOne();

        if (!$salesOrderItemGiftCard) {
            throw new GiftCardSalesMetadataNotFoundException(
                sprintf('Giftcard Metadata with ID %d is missing', $idSalesOrderItem)
            );
        }

        return $salesOrderItemGiftCard;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard $salesOrderItemGiftCardEntity
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    protected function mapSalesOrderItemGiftCardToGiftCardTransfer(
        SpySalesOrderItemGiftCard $salesOrderItemGiftCardEntity,
        GiftCardTransfer $giftCardTransfer
    ) {
        $giftCardTransfer = $giftCardTransfer->setCode($salesOrderItemGiftCardEntity->getCode());

        return $giftCardTransfer;
    }
}
