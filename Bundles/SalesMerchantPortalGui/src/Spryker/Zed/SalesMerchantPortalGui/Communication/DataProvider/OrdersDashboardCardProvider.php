<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\DashboardActionButtonTransfer;
use Generated\Shared\Transfer\DashboardCardTransfer;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig;
use Twig\Environment;

class OrdersDashboardCardProvider implements OrdersDashboardCardProviderInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface
     */
    protected $salesMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface
     */
    protected $routerFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig
     */
    protected $salesMerchantPortalGuiConfig;

    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface $salesMerchantPortalGuiRepository
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface $routerFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig $salesMerchantPortalGuiConfig
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(
        SalesMerchantPortalGuiRepositoryInterface $salesMerchantPortalGuiRepository,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        SalesMerchantPortalGuiToRouterFacadeInterface $routerFacade,
        SalesMerchantPortalGuiConfig $salesMerchantPortalGuiConfig,
        Environment $twigEnvironment
    ) {
        $this->salesMerchantPortalGuiRepository = $salesMerchantPortalGuiRepository;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->routerFacade = $routerFacade;
        $this->salesMerchantPortalGuiConfig = $salesMerchantPortalGuiConfig;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @return \Generated\Shared\Transfer\DashboardCardTransfer
     */
    public function getDashboardCard(): DashboardCardTransfer
    {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        $ordersDashboardCardCounts = $this->salesMerchantPortalGuiRepository->getOrdersDashboardCardCounts($idMerchant);
        $ordersDashboardCardCounts['newOrdersLimit'] = $this->salesMerchantPortalGuiConfig->getDashboardNewOrdersLimit();
        $ordersDashboardCardCounts['ordersStoresCountData'] = $this->salesMerchantPortalGuiRepository->getOrdersStoresCountData(
            $idMerchant
        );

        $title = $this->twigEnvironment->render(
            '@SalesMerchantPortalGui/Partials/orders_dashboard_card_title.twig',
            $ordersDashboardCardCounts
        );
        $content = $this->twigEnvironment->render(
            '@SalesMerchantPortalGui/Partials/orders_dashboard_card_content.twig',
            $ordersDashboardCardCounts
        );

        return (new DashboardCardTransfer())
            ->setTitle($title)
            ->setContent($content)
            ->setActionButtons(new ArrayObject([
                (new DashboardActionButtonTransfer())
                    ->setTitle('Manage Orders')
                    ->setUrl($this->routerFacade->getRouter()->generate('sales-merchant-portal-gui:orders')),
            ]));
    }
}
