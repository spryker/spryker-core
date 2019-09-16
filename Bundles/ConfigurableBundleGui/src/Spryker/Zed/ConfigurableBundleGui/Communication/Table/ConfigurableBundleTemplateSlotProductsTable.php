<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Table;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface;
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
     * @var \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected $productPropelQuery;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param int $idConfigurableBundleTemplateSlot
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productPropelQuery
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface $productListFacade
     */
    public function __construct(
        int $idConfigurableBundleTemplateSlot,
        SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery,
        SpyProductQuery $productPropelQuery,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade,
        ConfigurableBundleGuiToProductListFacadeInterface $productListFacade
    ) {
        $this->idConfigurableBundleTemplateSlot = $idConfigurableBundleTemplateSlot;
        $this->configurableBundleTemplateSlotPropelQuery = $configurableBundleTemplateSlotPropelQuery;
        $this->productPropelQuery = $productPropelQuery;
        $this->localeFacade = $localeFacade;
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            SpyProductTableMap::COL_ID_PRODUCT => 'Product ID',
            SpyProductTableMap::COL_SKU => 'SKU',
            SpyProductLocalizedAttributesTableMap::COL_NAME => 'Name',
        ]);

        $config->setSortable([
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setSearchable([
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
            $this->prepareQuery(),
            $config,
            true
        );

        return $configurableBundleTemplateSlotProducts->getData();
    }

    /**
     * @module Product
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function prepareQuery(): SpyProductQuery
    {
        $configurableBundleTemplateSlotProductIds = $this->getConfigurableBundleTemplateSlotProductIds();

        $this->productPropelQuery
            ->joinSpyProductLocalizedAttributes()
            ->where(sprintf(
                '%s = %s',
                SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE,
                $this->localeFacade->getCurrentLocale()->getIdLocale()
            ))
            ->filterByIdProduct_In($configurableBundleTemplateSlotProductIds)
            ->select([
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductTableMap::COL_SKU,
                SpyProductLocalizedAttributesTableMap::COL_NAME,
            ]);

        return $this->productPropelQuery;
    }

    /**
     * @return int[]
     */
    protected function getConfigurableBundleTemplateSlotProductIds(): array
    {
        $configurableBundleTemplateSlotEntity = $this->configurableBundleTemplateSlotPropelQuery
            ->findOneByIdConfigurableBundleTemplateSlot($this->idConfigurableBundleTemplateSlot);

        if (!$configurableBundleTemplateSlotEntity) {
            return [];
        }

        return $this->productListFacade->getProductConcreteIdsByProductListIds([$configurableBundleTemplateSlotEntity->getFkProductList()]);
    }
}
