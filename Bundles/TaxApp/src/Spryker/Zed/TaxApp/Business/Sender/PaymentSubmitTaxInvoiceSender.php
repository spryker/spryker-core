<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Sender;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SubmitPaymentTaxInvoiceTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToMessageBrokerFacadeInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;

class PaymentSubmitTaxInvoiceSender implements PaymentSubmitTaxInvoiceSenderInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToMessageBrokerFacadeInterface
     */
    protected TaxAppToMessageBrokerFacadeInterface $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected TaxAppToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface
     */
    protected TaxAppToSalesFacadeInterface $salesFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface
     */
    protected TaxAppMapperInterface $taxAppMapper;

    /**
     * @var array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface>
     */
    protected array $orderTaxAppExpanderPlugins;

    /**
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToMessageBrokerFacadeInterface $messageBrokerFacade
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface $taxAppMapper
     * @param array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface> $orderTaxAppExpanderPlugins
     */
    public function __construct(
        TaxAppToMessageBrokerFacadeInterface $messageBrokerFacade,
        TaxAppToStoreFacadeInterface $storeFacade,
        TaxAppToSalesFacadeInterface $salesFacade,
        TaxAppMapperInterface $taxAppMapper,
        array $orderTaxAppExpanderPlugins
    ) {
        $this->messageBrokerFacade = $messageBrokerFacade;
        $this->storeFacade = $storeFacade;
        $this->salesFacade = $salesFacade;
        $this->taxAppMapper = $taxAppMapper;
        $this->orderTaxAppExpanderPlugins = $orderTaxAppExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function sendSubmitPaymentTaxInvoiceMessage(OrderTransfer $orderTransfer): void
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrderOrFail();
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);

        if (!$orderTransfer) {
            $this->getLogger()->warning(sprintf('Order with ID `%s` not found', $idSalesOrder));

            return;
        }

        $orderTransfer = $this->executeOrderTaxAppExpanderPlugins($orderTransfer);

        $taxAppSaleTransfer = $this->taxAppMapper->mapOrderTransferToTaxAppSaleTransfer($orderTransfer, new TaxAppSaleTransfer());

        $submitPaymentTaxInvoiceTransfer = new SubmitPaymentTaxInvoiceTransfer();
        $submitPaymentTaxInvoiceTransfer->setSale($taxAppSaleTransfer);

        $this->setMessageAttributesTransfer($submitPaymentTaxInvoiceTransfer);

        $this->messageBrokerFacade->sendMessage($submitPaymentTaxInvoiceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SubmitPaymentTaxInvoiceTransfer $submitPaymentTaxInvoiceTransfer
     *
     * @return void
     */
    protected function setMessageAttributesTransfer(SubmitPaymentTaxInvoiceTransfer $submitPaymentTaxInvoiceTransfer): void
    {
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference($this->storeFacade->getCurrentStore()->getStoreReference());

        $submitPaymentTaxInvoiceTransfer->setMessageAttributes($messageAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeOrderTaxAppExpanderPlugins(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($this->orderTaxAppExpanderPlugins as $orderTaxAppExpanderPlugin) {
            $orderTransfer = $orderTaxAppExpanderPlugin->expand($orderTransfer);
        }

        return $orderTransfer;
    }
}
