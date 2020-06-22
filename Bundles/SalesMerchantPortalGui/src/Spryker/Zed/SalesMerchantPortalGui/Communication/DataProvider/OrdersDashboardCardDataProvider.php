<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\DashboardActionButtonTransfer;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig;
use Twig\Environment;

class OrdersDashboardCardDataProvider implements OrdersDashboardCardDataProviderInterface
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
     * @var int[]|null
     */
    protected static $ordersDashboardCardCountData;

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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->twigEnvironment->render(
            '@SalesMerchantPortalGui/Partials/orders_dashboard_card_title.twig',
            $this->getOrdersDashboardCardCountData()
        );
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $ordersDashboardCardCountData = $this->getOrdersDashboardCardCountData();
        $ordersDashboardCardCountData['newOrdersLimit'] = $this->salesMerchantPortalGuiConfig->getDashboardNewOrdersLimit();
        $ordersDashboardCardCountData['ordersStoresCountData'] = $this->salesMerchantPortalGuiRepository->getOrdersStoresCountData(
            $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant()
        );

        return $this->twigEnvironment->render(
            '@SalesMerchantPortalGui/Partials/orders_dashboard_card_content.twig',
            $ordersDashboardCardCountData
        );
    }

    /**
     * @return \Generated\Shared\Transfer\DashboardActionButtonTransfer[]
     */
    public function getActionButtons(): array
    {
        return [
            (new DashboardActionButtonTransfer())
                ->setTitle('Manage Orders')
                ->setUrl($this->routerFacade->getRouter()->generate('sales-merchant-portal-gui:orders')),
        ];
    }

    /**
     * @return int[]
     */
    protected function getOrdersDashboardCardCountData(): array
    {
        if (static::$ordersDashboardCardCountData) {
            return static::$ordersDashboardCardCountData;
        }

        static::$ordersDashboardCardCountData = $this->salesMerchantPortalGuiRepository->getOrdersDashboardCardCountData(
            $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant()
        );

        return static::$ordersDashboardCardCountData;
    }
}
