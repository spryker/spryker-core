<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProviderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface;
use Twig\Environment;

class MerchantDashboardCardDataProvider implements MerchantDashboardCardDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface
     */
    protected MerchantRelationshipReaderInterface $merchantRelationshipReader;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Twig\Environment
     */
    protected Environment $twigEnvironment;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProviderInterface
     */
    protected MerchantRelationshipDashboardGuiTableConfigurationProviderInterface $merchantRelationshipDashboardGuiTableConfigurationProvider;

    /**
     * @var list<\Spryker\Zed\MerchantRelationshipMerchantPortalGuiExtension\Dependency\Plugin\MerchantRelationshipMerchantDashboardCardExpanderPluginInterface>
     */
    protected array $merchantRelationshipMerchantDashboardCardExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Reader\MerchantRelationshipReaderInterface $merchantRelationshipReader
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Twig\Environment $twigEnvironment
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipDashboardGuiTableConfigurationProviderInterface $merchantRelationshipDashboardGuiTableConfigurationProvider
     * @param list<\Spryker\Zed\MerchantRelationshipMerchantPortalGuiExtension\Dependency\Plugin\MerchantRelationshipMerchantDashboardCardExpanderPluginInterface> $merchantRelationshipMerchantDashboardCardExpanderPlugins
     */
    public function __construct(
        MerchantRelationshipReaderInterface $merchantRelationshipReader,
        MerchantRelationshipMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        Environment $twigEnvironment,
        MerchantRelationshipDashboardGuiTableConfigurationProviderInterface $merchantRelationshipDashboardGuiTableConfigurationProvider,
        array $merchantRelationshipMerchantDashboardCardExpanderPlugins
    ) {
        $this->merchantRelationshipReader = $merchantRelationshipReader;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->twigEnvironment = $twigEnvironment;
        $this->merchantRelationshipDashboardGuiTableConfigurationProvider = $merchantRelationshipDashboardGuiTableConfigurationProvider;
        $this->merchantRelationshipMerchantDashboardCardExpanderPlugins = $merchantRelationshipMerchantDashboardCardExpanderPlugins;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function getMerchantRelationDashboardCard(): MerchantDashboardCardTransfer
    {
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipReader->getMerchantRelationshipCollection();
        $totalMerchantRelationshipCount = $merchantRelationshipCollectionTransfer->getMerchantRelationships()->count();

        $title = $this->twigEnvironment->render(
            '@MerchantRelationshipMerchantPortalGui/Partials/merchant_relationship_merchant_dashboard_card_title.twig',
        );
        $content = $this->twigEnvironment->render(
            '@MerchantRelationshipMerchantPortalGui/Partials/merchant_relationship_merchant_dashboard_card_content.twig',
            [
                'totalMerchantRelationshipCount' => $totalMerchantRelationshipCount,
            ],
        );
        $content .= $this->twigEnvironment->render(
            '@MerchantRelationshipMerchantPortalGui/Partials/merchant_relationship_merchant_dashboard_table.twig',
            [
                'totalMerchantRelationshipCount' => $totalMerchantRelationshipCount,
                'merchantRelationshipDashboardTableConfiguration' => $this->merchantRelationshipDashboardGuiTableConfigurationProvider
                    ->getConfiguration($merchantRelationshipCollectionTransfer),
            ],
        );

        $merchantDashboardCardTransfer = (new MerchantDashboardCardTransfer())
            ->setTitle($title)
            ->setContent($content);

        return $this->executeMerchantRelationshipMerchantDashboardCardExpanderPlugins($merchantDashboardCardTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    protected function executeMerchantRelationshipMerchantDashboardCardExpanderPlugins(
        MerchantDashboardCardTransfer $merchantDashboardCardTransfer
    ): MerchantDashboardCardTransfer {
        foreach ($this->merchantRelationshipMerchantDashboardCardExpanderPlugins as $merchantRelationshipMerchantDashboardCardExpanderPlugin) {
            $merchantDashboardCardTransfer = $merchantRelationshipMerchantDashboardCardExpanderPlugin->expand($merchantDashboardCardTransfer);
        }

        return $merchantDashboardCardTransfer;
    }
}
