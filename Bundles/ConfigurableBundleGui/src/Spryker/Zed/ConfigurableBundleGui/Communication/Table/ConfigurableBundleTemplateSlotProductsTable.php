<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Table;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ConfigurableBundleTemplateSlotProductsTable extends AbstractTable
{
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::slotProductsTableAction()
     */
    protected const ROUTE_TABLE_RENDERING = '/slot-products-table?%s=%s';

    /**
     * @var int
     */
    protected $idConfigurableBundleTemplateSlot;

    /**
     * @var \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected $configurableBundleTemplateSlotPropelQuery;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param int $idConfigurableBundleTemplateSlot
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        int $idConfigurableBundleTemplateSlot,
        SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->idConfigurableBundleTemplateSlot = $idConfigurableBundleTemplateSlot;
        $this->configurableBundleTemplateSlotPropelQuery = $configurableBundleTemplateSlotPropelQuery;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyProductTableMap::COL_ID_PRODUCT => 'ID',
            SpyProductTableMap::COL_SKU => 'SKU',
            SpyProductLocalizedAttributesTableMap::COL_NAME => 'Name',
        ]);

        $config->setSortable([
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setUrl(
            sprintf(
                static::ROUTE_TABLE_RENDERING,
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
                $this->idConfigurableBundleTemplateSlot
            )
        );

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $configurableBundleTemplateSlotProducts = $this->runQuery(
            $this->prepareQuery($this->configurableBundleTemplateSlotPropelQuery),
            $config,
            true
        );

        return $configurableBundleTemplateSlotProducts->getData();
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function prepareQuery(SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery): SpyConfigurableBundleTemplateSlotQuery
    {
        $configurableBundleTemplateSlotPropelQuery
            ->filterByIdConfigurableBundleTemplateSlot($this->idConfigurableBundleTemplateSlot)
            ->joinSpyProductList()
            ->useSpyProductListQuery()
                ->joinSpyProductListProductConcrete()
                ->useSpyProductListProductConcreteQuery()
                    ->joinSpyProduct()
                    ->useSpyProductQuery()
                        ->joinSpyProductLocalizedAttributes()
                        ->where(sprintf(
                            '%s = %s',
                            SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE,
                            $this->localeFacade->getCurrentLocale()->getIdLocale()
                        ))
                    ->endUse()
                ->endUse()
            ->endUse()
            ->select([
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::COL_SKU,
                SpyProductLocalizedAttributesTableMap::COL_NAME,
            ]);

        return $configurableBundleTemplateSlotPropelQuery;
    }
}
