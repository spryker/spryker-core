<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MerchantCreateOfferFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected MerchantFacadeInterface $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface
     */
    protected MerchantStockFacadeInterface $merchantStockFacade;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected SelfServicePortalConfig $config;

    /**
     * @param \Spryker\Zed\Merchant\Business\MerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface $merchantStockFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     */
    public function __construct(
        MerchantFacadeInterface $merchantFacade,
        MerchantStockFacadeInterface $merchantStockFacade,
        SelfServicePortalConfig $config
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->merchantStockFacade = $merchantStockFacade;
        $this->config = $config;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onSubmit(FormEvent $event): void
    {
        $productOfferTransfer = $event->getData();

        if (!($productOfferTransfer instanceof ProductOfferTransfer)) {
            return;
        }

        $merchantCollectionTransfer = $this->merchantFacade->get(
            (new MerchantCriteriaTransfer())->setMerchantReference($this->config->getDefaultMerchantReference()),
        );

        $merchant = $merchantCollectionTransfer->getMerchants()->offsetGet(0);

        $stockCollectionTransfer = $this->merchantStockFacade->get(
            (new MerchantStockCriteriaTransfer())
                ->setIsDefault(true)
                ->setIdMerchant(
                    $merchant->getIdMerchant(),
                ),
        );

        $productOfferTransfer->getProductOfferStocks()
            ->offsetGet(0)
            ->setStock(
                $stockCollectionTransfer->getStocks()->offsetGet(0),
            );

        $productOfferTransfer->setMerchantReference($merchant->getMerchantReference());
    }
}
