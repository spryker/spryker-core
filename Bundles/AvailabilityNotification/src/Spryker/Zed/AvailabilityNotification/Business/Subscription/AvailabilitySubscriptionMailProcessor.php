<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
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
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(AvailabilityNotificationRepositoryInterface $availabilityNotificationRepository, AvailabilityNotificationToMailFacadeInterface $mailFacade, AvailabilityNotificationToProductFacadeInterface $productFacade, AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->availabilityNotificationRepository = $availabilityNotificationRepository;
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function processProductBecomeAvailableSubscription(string $sku, StoreTransfer $storeTransfer, ProductConcreteTransfer $productConcreteTransfer): void
    {
        $availabilitySubscriptionCollectionTransfer = $this->availabilityNotificationRepository
            ->findBySkuAndStore($productConcreteTransfer->getSku(), $storeTransfer->getIdStore());

        foreach ($availabilitySubscriptionCollectionTransfer->getAvailabilitySubscriptions() as $availabilitySubscription) {
            $mailTransfer = new MailTransfer();
            $mailTransfer->setAvailabilitySubscription($availabilitySubscription);
            $mailTransfer->setProductConcrete($productConcreteTransfer);
            $productAbstractTransfer = $this->productFacade->findProductAbstractById($productConcreteTransfer->getFkProductAbstract());
            $productPrice = $this->priceProductFacade->findProductPriceBySku($sku);
            $mailTransfer->setProductPrice($productPrice);
            $mailTransfer->setCurrencyIsoCode($storeTransfer->getDefaultCurrencyIsoCode());

            if ($productAbstractTransfer) {
                $productUrlTransfer = $this->productFacade->getProductUrl($productAbstractTransfer);
                foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
                    if ($availabilitySubscription->getLocale()->getIdLocale() === $localizedUrlTransfer->getLocale()->getIdLocale()) {
                        $yvesBaseUrl = Config::get(ApplicationConstants::BASE_URL_YVES);
                        $productFullUrl = $yvesBaseUrl . $localizedUrlTransfer->getUrl();
                        $mailTransfer->setProductUrl($productFullUrl);
                    }
                }
            }

            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
                if ($availabilitySubscription->getLocale()->getIdLocale() === $localizedAttributesTransfer->getLocale()->getIdLocale()) {
                    $mailTransfer->setLocalizedAttributes($localizedAttributesTransfer);
                }
            }

            $mailTransfer->setType(AvailabilityNotificationMailTypePlugin::MAIL_TYPE);
            $this->mailFacade->handleMail($mailTransfer);
        }
    }
}
