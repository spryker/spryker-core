<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationUnsubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface;
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
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

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
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     */
    public function __construct(
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToProductFacadeInterface $productFacade,
        AvailabilityNotificationToMoneyFacadeInterface $moneyFacade,
        AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade,
        UrlGeneratorInterface $urlGenerator,
        AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
    ) {
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductFacade = $priceProductFacade;
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

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscribedMailTypePlugin::MAIL_TYPE)
            ->setProduct($productConcreteTransfer)
            ->setProductAttributes($productAttributes)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink)
            ->setLocale($availabilitySubscriptionTransfer->getLocale());

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

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationUnsubscribedMailTypePlugin::MAIL_TYPE)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setLocale($availabilitySubscriptionTransfer->getLocale())
            ->setProductAttributes($productAttributes);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationTransfer $availabilityNotificationTransfer
     *
     * @return void
     */
    public function sendProductBecomeAvailableMail(AvailabilityNotificationTransfer $availabilityNotificationTransfer): void
    {
        $availabilitySubscriptionCollectionTransfer = $this->availabilityNotificationRepository
            ->findBySkuAndStore(
                $availabilityNotificationTransfer->getSku(),
                $availabilityNotificationTransfer->getStore()->getIdStore()
            );

        foreach ($availabilitySubscriptionCollectionTransfer->getAvailabilitySubscriptions() as $availabilitySubscription) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilitySubscription->getSku());
            $productAttributes = $this->getProductAttributes(
                $productConcreteTransfer,
                $availabilitySubscription->getLocale()
            );

            $mailTransfer = (new MailTransfer())
                ->setType(AvailabilityNotificationMailTypePlugin::MAIL_TYPE)
                ->setLocale($availabilitySubscription->getLocale())
                ->setAvailabilitySubscription($availabilitySubscription)
                ->setProductConcrete($productConcreteTransfer)
                ->setProductAttributes($productAttributes);

            $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilitySubscription);
            $mailTransfer->setAvailabilityUnsubscriptionLink($unsubscriptionLink);

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
            'price' => $this->findProductPrice($productConcreteTransfer),
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
     *
     * @return string|null
     */
    protected function findProductPrice(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $amount = $this->priceProductFacade->findPriceBySku($productConcreteTransfer->getSku());

        if ($amount === null) {
            return null;
        }

        $priceTransfer = $this->moneyFacade->fromInteger($amount);

        return $this->moneyFacade->formatWithSymbol($priceTransfer);
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
