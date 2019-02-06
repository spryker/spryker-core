<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionMailDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscriptionMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationUnsubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class AvailabilityNotificationSender implements AvailabilityNotificationSenderInterface
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
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     */
    public function __construct(
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToProductFacadeInterface $productFacade,
        UrlGeneratorInterface $urlGenerator,
        AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
    ) {
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->urlGenerator = $urlGenerator;
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscriptionMail(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilityNotificationSubscriptionTransfer->getSku());
        $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilityNotificationSubscriptionTransfer);

        $mailData = (new AvailabilityNotificationSubscriptionMailDataTransfer())
            ->setProductConcrete($productConcreteTransfer)
            ->setProductName($this->getProductName($productConcreteTransfer, $availabilityNotificationSubscriptionTransfer->getLocale()))
            ->setProductImageUrl($this->findProductImage($productConcreteTransfer))
            ->setProductUrl($this->findProductUrl($productConcreteTransfer, $availabilityNotificationSubscriptionTransfer->getLocale()))
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscriptionMailTypePlugin::AVAILABILITY_NOTIFICATION_SUBSCRIPTION_MAIL)
            ->setLocale($availabilityNotificationSubscriptionTransfer->getLocale())
            ->setAvailabilityNotificationSubscriptionMailData($mailData);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return void
     */
    public function sendUnsubscriptionMail(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilityNotificationSubscriptionTransfer->getSku());
        $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilityNotificationSubscriptionTransfer);

        $mailData = (new AvailabilityNotificationSubscriptionMailDataTransfer())
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink)
            ->setProductName($this->getProductName($productConcreteTransfer, $availabilityNotificationSubscriptionTransfer->getLocale()))
            ->setProductImageUrl($this->findProductImage($productConcreteTransfer))
            ->setProductUrl($this->findProductUrl($productConcreteTransfer, $availabilityNotificationSubscriptionTransfer->getLocale()));

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationUnsubscribedMailTypePlugin::AVAILABILITY_NOTIFICATION_UNSUBSCRIBED_MAIL)
            ->setLocale($availabilityNotificationSubscriptionTransfer->getLocale())
            ->setAvailabilityNotificationSubscriptionMailData($mailData);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function sendProductBecomeAvailableMail(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void
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
                ->setProductName($this->getProductName($productConcreteTransfer, $availabilityNotificationSubscription->getLocale()))
                ->setProductImageUrl($this->findProductImage($productConcreteTransfer))
                ->setProductUrl($this->findProductUrl($productConcreteTransfer, $availabilityNotificationSubscription->getLocale()))
                ->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

            $mailTransfer = (new MailTransfer())
                ->setType(AvailabilityNotificationMailTypePlugin::AVAILABILITY_NOTIFICATION_MAIL)
                ->setLocale($availabilityNotificationSubscription->getLocale())
                ->setAvailabilityNotificationSubscriptionMailData($mailData);

            $this->mailFacade->handleMail($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getProductName(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): string {
        $attributes = [];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                $attributes = array_merge($attributes, $localizedAttributes->toArray());
            }
        }

        return $attributes['name'] ?? '';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function findProductUrl(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($productConcreteTransfer->getFkProductAbstract());

        if ($productAbstractTransfer === null) {
            return null;
        }

        $productUrlTransfer = $this->productFacade->getProductUrl($productAbstractTransfer);

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            if ($localeTransfer->getIdLocale() === $localizedUrlTransfer->getLocale()->getIdLocale()) {
                return $this->urlGenerator->generateProductUrl($localizedUrlTransfer);
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    protected function findProductImage(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $imageSetTransfer = current($productConcreteTransfer->getImageSets());

        if ($imageSetTransfer === false) {
            return null;
        }

        $productImageTransfer = current($imageSetTransfer->getProductImages());

        if ($productImageTransfer === false) {
            return null;
        }

        return $productImageTransfer->getExternalUrlLarge();
    }
}
