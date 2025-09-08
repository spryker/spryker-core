<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MerchantDashboardActionButtonTransfer;
use Generated\Shared\Transfer\MerchantDashboardCardTransfer;
use Generated\Shared\Transfer\MerchantProductCountsTransfer;
use Generated\Shared\Transfer\ProductsDashboardCardCriteriaTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig;
use Twig\Environment;

class ProductsDashboardCardDataProvider implements ProductsDashboardCardDataProviderInterface
{
    /**
     * @var string
     */
    protected const CARD_TITLE = 'Products';

    /**
     * @var string
     */
    protected const TITLE_TEMPLATE = '@ProductMerchantPortalGui/Partials/dashboard/products_card_title.twig';

    /**
     * @var string
     */
    protected const CONTENT_TEMPLATE = '@ProductMerchantPortalGui/Partials/dashboard/products_card_content.twig';

    /**
     * @var string
     */
    protected const LABEL_MANAGE_PRODUCTS = 'Manage Products';

    /**
     * @see \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsController::indexAction()
     *
     * @var string
     */
    protected const URL_PRODUCTS = '/product-merchant-portal-gui/products';

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig $config
     * @param \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Twig\Environment $twig
     */
    public function __construct(
        protected ProductMerchantPortalGuiConfig $config,
        protected ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository,
        protected ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        protected Environment $twig
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function getProductsCard(): MerchantDashboardCardTransfer
    {
        $merchantProductCountsTransfer = $this->productMerchantPortalGuiRepository->getProductsDashboardCardCounts(
            $this->createProductsDashboardCardCriteria(),
        );

        $title = $this->twig->render(
            static::TITLE_TEMPLATE,
            $this->getTitleTemplateParams($merchantProductCountsTransfer),
        );

        $content = $this->twig->render(
            static::CONTENT_TEMPLATE,
            $this->getContentTemplateParams($merchantProductCountsTransfer),
        );

        return (new MerchantDashboardCardTransfer())
            ->setTitle($title)
            ->setContent($content)
            ->setActionButtons($this->getActionButtons());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCountsTransfer $merchantProductCountsTransfer
     *
     * @return array<string, mixed>
     */
    protected function getTitleTemplateParams(MerchantProductCountsTransfer $merchantProductCountsTransfer): array
    {
        return [
            'title' => static::CARD_TITLE,
            'totalCount' => $merchantProductCountsTransfer->getTotal(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCountsTransfer $merchantProductCountsTransfer
     *
     * @return array<string, mixed>
     */
    protected function getContentTemplateParams(MerchantProductCountsTransfer $merchantProductCountsTransfer): array
    {
        return [
            'merchantProductCounts' => $merchantProductCountsTransfer,
            'expiringProductsDaysThreshold' => $this->config->getDashboardExpiringProductsDaysThreshold(),
        ];
    }

    /**
     * @return \ArrayObject<\Generated\Shared\Transfer\MerchantDashboardActionButtonTransfer>
     */
    protected function getActionButtons(): ArrayObject
    {
        return new ArrayObject([
            (new MerchantDashboardActionButtonTransfer())
                ->setTitle(static::LABEL_MANAGE_PRODUCTS)
                ->setUrl(static::URL_PRODUCTS),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductsDashboardCardCriteriaTransfer
     */
    protected function createProductsDashboardCardCriteria(): ProductsDashboardCardCriteriaTransfer
    {
        $merchantUser = $this->merchantUserFacade->getCurrentMerchantUser();

        return (new ProductsDashboardCardCriteriaTransfer())
            ->setIdMerchant($merchantUser->getIdMerchantOrFail())
            ->setLowStockThreshold($this->config->getDashboardLowStockThreshold())
            ->setExpiringProductsDaysThreshold($this->config->getDashboardExpiringProductsDaysThreshold());
    }
}
