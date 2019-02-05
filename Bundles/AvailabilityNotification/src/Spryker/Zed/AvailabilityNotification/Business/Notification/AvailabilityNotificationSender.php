<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Notification;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionMailDataTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
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
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscriptionMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilitySubscriptionTransfer->getSku());
        $productAttributes = $this->getProductAttributes(
            $productConcreteTransfer,
            $availabilitySubscriptionTransfer->getLocale()
        );
        $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilitySubscriptionTransfer);

        $mailData = (new AvailabilitySubscriptionMailDataTransfer())
            ->setProduct($productConcreteTransfer)
            ->setProductAttributes($productAttributes)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscriptionMailTypePlugin::AVAILABILITY_NOTIFICATION_SUBSCRIPTION_MAIL)
            ->setLocale($availabilitySubscriptionTransfer->getLocale())
            ->setAvailabilitySubscriptionMailData($mailData);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendUnsubscriptionMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilitySubscriptionTransfer->getSku());
        $productAttributes = $this->getProductAttributes(
            $productConcreteTransfer,
            $availabilitySubscriptionTransfer->getLocale()
        );
        $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilitySubscriptionTransfer);

        $mailData = (new AvailabilitySubscriptionMailDataTransfer())
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink)
            ->setProductAttributes($productAttributes);

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationUnsubscribedMailTypePlugin::AVAILABILITY_NOTIFICATION_UNSUBSCRIBED_MAIL)
            ->setLocale($availabilitySubscriptionTransfer->getLocale())
            ->setAvailabilitySubscriptionMailData($mailData);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer
     *
     * @return void
     */
    public function sendProductBecomeAvailableMail(AvailabilityNotificationDataTransfer $availabilityNotificationDataTransfer): void
    {
        $availabilitySubscriptions = $this->availabilityNotificationRepository
            ->findBySkuAndStore(
                $availabilityNotificationDataTransfer->getSku(),
                $availabilityNotificationDataTransfer->getStore()->getIdStore()
            );

        foreach ($availabilitySubscriptions as $availabilitySubscription) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilitySubscription->getSku());
            $productAttributes = $this->getProductAttributes(
                $productConcreteTransfer,
                $availabilitySubscription->getLocale()
            );
            $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilitySubscription);

            $mailData = (new AvailabilitySubscriptionMailDataTransfer())
                ->setAvailabilitySubscription($availabilitySubscription)
                ->setProductConcrete($productConcreteTransfer)
                ->setProductAttributes($productAttributes)
                ->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

            $mailTransfer = (new MailTransfer())
                ->setType(AvailabilityNotificationMailTypePlugin::AVAILABILITY_NOTIFICATION_MAIL)
                ->setLocale($availabilitySubscription->getLocale())
                ->setAvailabilitySubscriptionMailData($mailData);

            $this->mailFacade->handleMail($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getProductAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): array {
        $attributes = [
            'image' => $this->findProductImage($productConcreteTransfer),
            'url' => $this->findProductUrl($productConcreteTransfer, $localeTransfer),
        ];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                $attributes = array_merge($attributes, $localizedAttributes->toArray());
            }
        }

        return $attributes;
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
