<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;

class AvailabilitySubscriptionMailProcessor implements AvailabilitySubscriptionMailProcessorInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected $availabilityNotificationRepository;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $config
     */
    public function __construct(AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository, AvailabilityNotificationToMailFacadeInterface $mailFacade, AvailabilityNotificationToProductFacadeInterface $productFacade, AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade, AvailabilityNotificationConfig $config)
    {
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->config = $config;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function processProductBecomeAvailableSubscription(string $sku, StoreTransfer $storeTransfer): void
    {
        $availabilitySubscriptionCollectionTransfer = $this->availabilityNotificationRepository
            ->findBySkuAndStore($sku, $storeTransfer->getIdStore());

        foreach ($availabilitySubscriptionCollectionTransfer->getAvailabilitySubscriptions() as $availabilitySubscription) {
            $mailTransfer = new MailTransfer();
            $mailTransfer->setAvailabilitySubscription($availabilitySubscription);
            $productConcreteTransfer = $this->productFacade->getProductConcrete($sku);
            $mailTransfer->setProductConcrete($productConcreteTransfer);
            $mailTransfer = $this->setProductPrice($mailTransfer, $productConcreteTransfer->getSku());
            $mailTransfer->setCurrencyIsoCode($storeTransfer->getDefaultCurrencyIsoCode());
            $mailTransfer = $this->setProductUrl($mailTransfer, $productConcreteTransfer->getFkProductAbstract(), $availabilitySubscription);
            $mailTransfer = $this->setLocalizedAttributes($mailTransfer, $productConcreteTransfer, $availabilitySubscription);
            $mailTransfer->setType(AvailabilityNotificationMailTypePlugin::MAIL_TYPE);

            $this->mailFacade->handleMail($mailTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function setProductPrice(MailTransfer $mailTransfer, string $sku): MailTransfer
    {
        $mailTransfer->setProductPrice(
            $this->priceProductFacade->findProductPriceBySku($sku)
        );

        return $mailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param int $fkProductAbstract
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function setProductUrl(MailTransfer $mailTransfer, int $fkProductAbstract, AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): MailTransfer
    {
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($fkProductAbstract);

        if ($productAbstractTransfer) {
            $productUrlTransfer = $this->productFacade->getProductUrl($productAbstractTransfer);
            foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
                if ($availabilitySubscriptionTransfer->getLocale()->getIdLocale() === $localizedUrlTransfer->getLocale()->getIdLocale()) {
                    $yvesBaseUrl = $this->config->getBaseUrlYves();
                    $productFullUrl = $yvesBaseUrl . $localizedUrlTransfer->getUrl();
                    $mailTransfer->setProductUrl($productFullUrl);

                    return $mailTransfer;
                }
            }
        }

        return $mailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function setLocalizedAttributes(MailTransfer $mailTransfer, ProductConcreteTransfer $productConcreteTransfer, AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): MailTransfer
    {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            if ($availabilitySubscriptionTransfer->getLocale()->getIdLocale() === $localizedAttributesTransfer->getLocale()->getIdLocale()) {
                $mailTransfer->setLocalizedAttributes($localizedAttributesTransfer);

                return $mailTransfer;
            }
        }

        return $mailTransfer;
    }
}
