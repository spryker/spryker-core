<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionMailDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscriptionMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;

class AvailabilityNotificationSubscriptionSender implements AvailabilityNotificationSubscriptionSenderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface
     */
    protected $productAttributeFinder;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface $productAttributeFinder
     */
    public function __construct(
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToProductFacadeInterface $productFacade,
        UrlGeneratorInterface $urlGenerator,
        ProductAttributeFinderInterface $productAttributeFinder
    ) {
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->urlGenerator = $urlGenerator;
        $this->productAttributeFinder = $productAttributeFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function send(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilityNotificationSubscriptionTransfer->getSku());
        $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilityNotificationSubscriptionTransfer);

        $mailData = (new AvailabilityNotificationSubscriptionMailDataTransfer())
            ->setProductConcrete($productConcreteTransfer)
            ->setProductName($this->productAttributeFinder->findProductName($productConcreteTransfer, $availabilityNotificationSubscriptionTransfer->getLocale()))
            ->setProductImageUrl($this->productAttributeFinder->findExternalProductImage($productConcreteTransfer))
            ->setProductUrl($this->productAttributeFinder->findProductUrl($productConcreteTransfer, $availabilityNotificationSubscriptionTransfer->getLocale()))
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscriptionMailTypePlugin::AVAILABILITY_NOTIFICATION_SUBSCRIPTION_MAIL)
            ->setLocale($availabilityNotificationSubscriptionTransfer->getLocale())
            ->setAvailabilityNotificationSubscriptionMailData($mailData);

        $this->mailFacade->handleMail($mailTransfer);
    }
}
