<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Table;

use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateSlotTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ConfigurableBundleTemplateSlotTable extends AbstractTable
{
    protected const COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION = 'configurable_bundle_template_slot_name_translation';
    protected const COL_NUMBER_OF_ITEMS = 'number_of_items';
    protected const COL_ACTIONS = 'actions';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::slotTableAction()
     */
    protected const ROUTE_TABLE_RENDERING = '/slot-table?%s=%s';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::editAction()
     */
    protected const ROUTE_EDIT_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = '/configurable-bundle-gui/slot/edit';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::deleteAction()
     */
    protected const ROUTE_DELETE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = '/configurable-bundle-gui/slot/delete';

    /**
     * @var int
     */
    protected $idConfigurableBundleTemplate;

    /**
     * @var \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected $configurableBundleTemplateSlotPropelQuery;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param int $idConfigurableBundleTemplate
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface $productListFacade \
     */
    public function __construct(
        int $idConfigurableBundleTemplate,
        SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade,
        ConfigurableBundleGuiToProductListFacadeInterface $productListFacade
    ) {
        $this->idConfigurableBundleTemplate = $idConfigurableBundleTemplate;
        $this->configurableBundleTemplateSlotPropelQuery = $configurableBundleTemplateSlotPropelQuery;
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
            SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => 'Slot ID',
            static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION => 'Slot Name',
            static::COL_NUMBER_OF_ITEMS => 'Number of Items',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
            static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION,
        ]);

        $config->setSearchable([
            SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
            SpyGlossaryTranslationTableMap::COL_VALUE,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setUrl(
            sprintf(
                static::ROUTE_TABLE_RENDERING,
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE,
                $this->idConfigurableBundleTemplate
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
        /** @var \Propel\Runtime\Collection\ArrayCollection $configurableBundleTemplateSlotCollection */
        $configurableBundleTemplateSlotCollection = $this->runQuery(
            $this->prepareQuery($this->configurableBundleTemplateSlotPropelQuery),
            $config,
            true
        );

        if (!$configurableBundleTemplateSlotCollection->count()) {
            return [];
        }

        return $this->expandConfigurableBundleTemplateSlotCollectionWithNumberOfItemsAndActions($configurableBundleTemplateSlotCollection)->toArray();
    }

    /**
     * @module Glossary
     * @module ConfigurableBundle
     * @module ProductList
     *
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function prepareQuery(SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery): SpyConfigurableBundleTemplateSlotQuery
    {
        $configurableBundleTemplateSlotPropelQuery
            ->filterByFkConfigurableBundleTemplate($this->idConfigurableBundleTemplate)
            ->addJoin(SpyConfigurableBundleTemplateSlotTableMap::COL_NAME, SpyGlossaryKeyTableMap::COL_KEY, Criteria::INNER_JOIN)
            ->addJoin(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY, SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY, Criteria::INNER_JOIN)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION)
            ->innerJoinSpyProductList()
            ->select([
                SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
                SpyConfigurableBundleTemplateSlotTableMap::COL_FK_PRODUCT_LIST,
                static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION,
            ]);

        return $configurableBundleTemplateSlotPropelQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ArrayCollection $configurableBundleTemplateSlotCollection
     *
     * @return \Propel\Runtime\Collection\ArrayCollection
     */
    protected function expandConfigurableBundleTemplateSlotCollectionWithNumberOfItemsAndActions(ArrayCollection $configurableBundleTemplateSlotCollection): ArrayCollection
    {
        foreach ($configurableBundleTemplateSlotCollection as $index => $configurableBundleTemplateSlotData) {
            $configurableBundleTemplateSlotCollection[$index][static::COL_ACTIONS] = $this->buildLinks($configurableBundleTemplateSlotData);
            $configurableBundleTemplateSlotCollection[$index][static::COL_NUMBER_OF_ITEMS] = count(
                $this->productListFacade->getProductConcreteIdsByProductListIds(
                    [$configurableBundleTemplateSlotData[SpyConfigurableBundleTemplateSlotTableMap::COL_FK_PRODUCT_LIST]]
                )
            );
        }

        return $configurableBundleTemplateSlotCollection;
    }

    /**
     * @param array $configurableBundleTemplateSlotData
     *
     * @return string
     */
    protected function buildLinks(array $configurableBundleTemplateSlotData): string
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate(static::ROUTE_EDIT_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $configurableBundleTemplateSlotData[SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT],
            ]),
            'Edit'
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::ROUTE_DELETE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $configurableBundleTemplateSlotData[SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT],
            ]),
            'Delete'
        );

        return implode(' ', $buttons);
    }
}
