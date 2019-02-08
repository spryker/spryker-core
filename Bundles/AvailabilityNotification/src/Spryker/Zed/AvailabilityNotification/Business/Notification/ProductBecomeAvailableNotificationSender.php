<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionMailDataTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class ProductBecomeAvailableNotificationSender implements ProductBecomeAvailableNotificationSenderInterface
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
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $availabilityNotificationRepository;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface
     */
    protected $productAttributeFinder;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     * @param \Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface $productAttributeFinder
     */
    public function __construct(
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToProductFacadeInterface $productFacade,
        UrlGeneratorInterface $urlGenerator,
        AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository,
        ProductAttributeFinderInterface $productAttributeFinder
    ) {
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->urlGenerator = $urlGenerator;
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
        $this->productAttributeFinder = $productAttributeFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function send(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void
    {
        $availabilityNotificationSubscriptions = $this->availabilityNotificationRepository
            ->findBySkuAndStore(
                $availabilityNotificationDataTransfer->getSku(),
                $availabilityNotificationDataTransfer->getStore()->getIdStore()
            );

        foreach ($availabilityNotificationSubscriptions as $availabilityNotificationSubscription) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilityNotificationSubscription->getSku());
            $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilityNotificationSubscription);

            $mailData = (new AvailabilityNotificationSubscriptionMailDataTransfer())
                ->setAvailabilityNotificationSubscription($availabilityNotificationSubscription)
                ->setProductConcrete($productConcreteTransfer)
                ->setProductName($this->productAttributeFinder->findProductName($productConcreteTransfer, $availabilityNotificationSubscription->getLocale()))
                ->setProductImageUrl($this->productAttributeFinder->findExternalProductImage($productConcreteTransfer))
                ->setProductUrl($this->productAttributeFinder->findProductUrl($productConcreteTransfer, $availabilityNotificationSubscription->getLocale()))
                ->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

            $mailTransfer = (new MailTransfer())
                ->setType(AvailabilityNotificationMailTypePlugin::AVAILABILITY_NOTIFICATION_MAIL)
                ->setLocale($availabilityNotificationSubscription->getLocale())
                ->setAvailabilityNotificationSubscriptionMailData($mailData);

            $this->mailFacade->handleMail($mailTransfer);
        }
    }
}
