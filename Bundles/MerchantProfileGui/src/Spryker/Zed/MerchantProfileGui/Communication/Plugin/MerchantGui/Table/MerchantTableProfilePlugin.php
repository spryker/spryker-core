<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantTableProfilePlugin extends AbstractPlugin implements MerchantTableActionExpanderPluginInterface, MerchantTableDataExpanderPluginInterface, MerchantTableHeaderExpanderPluginInterface, MerchantTableConfigExpanderPluginInterface
{
    protected const COL_IS_ACTIVE = 'is_active';
    protected const COL_IS_ACTIVE_LABEL = 'active';
    protected const ID_MERCHANT = SpyMerchantTableMap::COL_ID_MERCHANT;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $config->addRawColumn(static::COL_IS_ACTIVE);

        return $config;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function expandHeader(): array
    {
        return [static::COL_IS_ACTIVE => static::COL_IS_ACTIVE_LABEL];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [static::COL_IS_ACTIVE => $this->getIsActive($item)];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getActionButtonDefinitions(array $item): array
    {
        return [];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getIsActive(array $item): string
    {
        if (true) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param string $title
     * @param string|null $class
     *
     * @return string
     */
    protected function generateLabel(string $title, ?string $class): string
    {
        return $this->getFactory()->getTwigEnvironment()->render('label.twig', [
            'title' => $title,
            'class' => $class,
        ]);
    }

//    /**
//     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
//     *
//     * @return string
//     */
//    protected function generateActivateButton(SpyProductSet $productSetEntity)
//    {
//        return $this->generateViewButton(
//            Url::generate('/product-set-gui/edit/activate', [
//                EditController::PARAM_ID => $productSetEntity->getIdProductSet(),
//            ]),
//            'Activate'
//        );
//    }
}
