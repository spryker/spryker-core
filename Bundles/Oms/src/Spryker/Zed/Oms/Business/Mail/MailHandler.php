<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Mail;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Communication\Plugin\Mail\OrderConfirmationMailTypePlugin;
use Spryker\Zed\Oms\Communication\Plugin\Mail\OrderShippedMailTypePlugin;
use Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface;

class MailHandler
{
    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface
     */
    protected $saleFacade;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[]
     */
    protected $orderMailExpanderPlugins;

    /**
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface $saleFacade
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface $mailFacade
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[] $orderMailExpanderPlugins
     */
    public function __construct(
        OmsToSalesInterface $saleFacade,
        OmsToMailInterface $mailFacade,
        array $orderMailExpanderPlugins
    ) {
        $this->saleFacade = $saleFacade;
        $this->mailFacade = $mailFacade;
        $this->orderMailExpanderPlugins = $orderMailExpanderPlugins;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);

        $mailTransfer = (new MailTransfer())
            ->setOrder($orderTransfer)
            ->setType(OrderConfirmationMailTypePlugin::MAIL_TYPE)
            ->setLocale($orderTransfer->getLocale());

        $mailTransfer = $this->expandOrderMailTransfer($mailTransfer, $orderTransfer);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderShippedMail(SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);

        $mailTransfer = new MailTransfer();
        $mailTransfer->setOrder($orderTransfer);
        $mailTransfer->setType(OrderShippedMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setLocale($orderTransfer->getLocale());

        $mailTransfer = $this->expandOrderMailTransfer($mailTransfer, $orderTransfer);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->saleFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());

        $shippingAddressTransfer = $this->mapShippingAddressEntityToShippingAddressTransfer($salesOrderEntity);
        $orderTransfer->setShippingAddress($shippingAddressTransfer);

        $billingAddressTransfer = $this->getBillingAddressTransfer($salesOrderEntity);
        $orderTransfer->setBillingAddress($billingAddressTransfer);

        if ($this->hasLocale($salesOrderEntity)) {
            $localeTransfer = $this->getLocaleTransfer($salesOrderEntity);
            $orderTransfer->setLocale($localeTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function mapShippingAddressEntityToShippingAddressTransfer(SpySalesOrder $salesOrderEntity): ?AddressTransfer
    {
        $shippingAddressEntity = $salesOrderEntity->getShippingAddress();

        if ($shippingAddressEntity === null) {
            return null;
        }

        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($shippingAddressEntity->toArray(), true);

        return $addressTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getBillingAddressTransfer(SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $salesOrderEntity->getBillingAddress();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($billingAddressEntity->toArray(), true);

        return $addressTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return bool
     */
    protected function hasLocale(SpySalesOrder $salesOrderEntity)
    {
        return ($salesOrderEntity->getLocale() !== null);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(SpySalesOrder $salesOrderEntity)
    {
        $localeEntity = $salesOrderEntity->getLocale();
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function expandOrderMailTransfer(MailTransfer $mailTransfer, OrderTransfer $orderTransfer): MailTransfer
    {
        foreach ($this->orderMailExpanderPlugins as $orderMailExpanderPlugin) {
            $mailTransfer = $orderMailExpanderPlugin->expand($mailTransfer, $orderTransfer);
        }

        return $mailTransfer;
    }
}
