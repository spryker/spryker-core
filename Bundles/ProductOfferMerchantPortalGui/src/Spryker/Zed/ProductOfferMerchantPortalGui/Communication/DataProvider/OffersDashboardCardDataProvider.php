<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\DashboardActionButtonTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig;
use Twig\Environment;

class OffersDashboardCardDataProvider implements OffersDashboardCardDataProviderInterface
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
     * @var int[]|null
     */
    protected static $offersDashboardCardData;

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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->twigEnvironment->render(
            '@ProductOfferMerchantPortalGui/Partials/offers_dashboard_card_title.twig',
            $this->getOffersDashboardCardData()
        );
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $offersDashboardCardData = $this->getOffersDashboardCardData();
        $offersDashboardCardData['expiringOffersLimit'] = $this->productOfferMerchantPortalGuiConfig->getDashboardExpiringOffersLimit();
        $offersDashboardCardData['lowStockThreshold'] = $this->productOfferMerchantPortalGuiConfig->getDashboardLowStockThreshold();
        $offersDashboardCardData['offersCountInactive'] = $offersDashboardCardData['offersCountTotal'] - $offersDashboardCardData['offersCountActive'];

        return $this->twigEnvironment->render(
            '@ProductOfferMerchantPortalGui/Partials/offers_dashboard_card_content.twig',
            $offersDashboardCardData
        );
    }

    /**
     * @return \Generated\Shared\Transfer\DashboardActionButtonTransfer[]
     */
    public function getActionButtons(): array
    {
        return [
            (new DashboardActionButtonTransfer())
                ->setTitle('Manage Offers')
                ->setUrl($this->routerFacade->getRouter()->generate('product-offer-merchant-portal-gui:offers')),
            (new DashboardActionButtonTransfer())
                ->setTitle('Add Offer')
                ->setUrl($this->routerFacade->getRouter()->generate('product-offer-merchant-portal-gui:create-offer')),
        ];
    }

    /**
     * @return int[]
     */
    protected function getOffersDashboardCardData(): array
    {
        if (static::$offersDashboardCardData) {
            return static::$offersDashboardCardData;
        }

        static::$offersDashboardCardData = $this->productOfferMerchantPortalGuiRepository->getOffersDashboardCardData(
            $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant()
        );

        return static::$offersDashboardCardData;
    }
}
