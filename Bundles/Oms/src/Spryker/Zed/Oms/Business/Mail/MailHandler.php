<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Mail;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
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
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface $salesFacade
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface $mailFacade
     */
    public function __construct(OmsToSalesInterface $salesFacade, OmsToMailInterface $mailFacade)
    {
        $this->saleFacade = $salesFacade;
        $this->mailFacade = $mailFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    public function sendOrderConfirmationMail(SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);

        $mailTransfer = new MailTransfer();
        $mailTransfer->setOrder($orderTransfer);
        $mailTransfer->setType(OrderConfirmationMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setLocale($orderTransfer->getLocale());

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

        $shippingAddressTransfer = $this->getShippingAddressTransfer($salesOrderEntity);
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
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getShippingAddressTransfer(SpySalesOrder $salesOrderEntity)
    {
        $shippingAddressEntity = $salesOrderEntity->getShippingAddress();
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
}
