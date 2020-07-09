<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\DashboardActionButtonTransfer;
use Generated\Shared\Transfer\DashboardCardTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig;
use Twig\Environment;

class OffersDashboardCardProvider implements OffersDashboardCardProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface
     */
    protected $productOfferMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface
     */
    protected $routerFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig
     */
    protected $productOfferMerchantPortalGuiConfig;

    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface $routerFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig $productOfferMerchantPortalGuiConfig
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToRouterFacadeInterface $routerFacade,
        ProductOfferMerchantPortalGuiConfig $productOfferMerchantPortalGuiConfig,
        Environment $twigEnvironment
    ) {
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->routerFacade = $routerFacade;
        $this->productOfferMerchantPortalGuiConfig = $productOfferMerchantPortalGuiConfig;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @return \Generated\Shared\Transfer\DashboardCardTransfer
     */
    public function getDashboardCard(): DashboardCardTransfer
    {
        $merchantProductOfferCountsTransfer = $this->productOfferMerchantPortalGuiRepository->getOffersDashboardCardCounts(
            $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant()
        );

        $title = $this->twigEnvironment->render(
            '@ProductOfferMerchantPortalGui/Partials/offers_dashboard_card_title.twig',
            [
                'merchantProductOfferCounts' => $merchantProductOfferCountsTransfer,
            ]
        );
        $content = $this->twigEnvironment->render(
            '@ProductOfferMerchantPortalGui/Partials/offers_dashboard_card_content.twig',
            [
                'merchantProductOfferCounts' => $merchantProductOfferCountsTransfer,
                'expiringOffersDaysThreshold' => $this->productOfferMerchantPortalGuiConfig->getDashboardExpiringOffersDaysThreshold(),
                'lowStockThreshold' => $this->productOfferMerchantPortalGuiConfig->getDashboardLowStockThreshold(),
            ]
        );

        return (new DashboardCardTransfer())
            ->setTitle($title)
            ->setContent($content)
            ->setActionButtons(new ArrayObject([
                (new DashboardActionButtonTransfer())
                    ->setTitle('Manage Offers')
                    ->setUrl($this->routerFacade->getRouter()->generate('product-offer-merchant-portal-gui:offers')),
                (new DashboardActionButtonTransfer())
                    ->setTitle('Add Offer')
                    ->setUrl($this->routerFacade->getRouter()->generate('product-offer-merchant-portal-gui:create-offer')),
            ]));
    }
}
