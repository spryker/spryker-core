<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Table;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateSlotTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ConfigurableBundleTemplateSlotTable extends AbstractTable
{
    protected const COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id_configurable_bundle_template_slot';
    protected const COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION = 'configurable_bundle_template_slot_name_translation';
    protected const COL_NUMBER_OF_ITEMS = 'number_of_items';
    protected const COL_ACTIONS = 'actions';
    protected const URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::slotTableAction()
     */
    protected const ROUTE_TABLE_RENDERING = '/slot-table';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::editAction()
     */
    protected const ROUTE_EDIT_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = '/configurable-bundle-gui/slot/edit';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::deleteAction()
     */
    protected const ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_DELETE = '/configurable-bundle-gui/template/delete';

    /**
     * @var \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected $configurableBundleTemplateSlotPropelQuery;

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     */
    public function __construct(SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery)
    {
        $this->configurableBundleTemplateSlotPropelQuery = $configurableBundleTemplateSlotPropelQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => 'Slot ID',
            static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION => 'Slot Name',
//            static::COL_NUMBER_OF_ITEMS => 'Number of Items',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
            static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION,
//            static::COL_NUMBER_OF_ITEMS,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setUrl(static::ROUTE_TABLE_RENDERING);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot[] $configurableBundleTemplateSlotEntityCollection */
        $configurableBundleTemplateSlotEntityCollection = $this->runQuery(
            $this->prepareQuery($this->configurableBundleTemplateSlotPropelQuery),
            $config,
            true
        );

        if (!$configurableBundleTemplateSlotEntityCollection->count()) {
            return [];
        }

        return $this->mapConfigurableBundleTemplateSlots($configurableBundleTemplateSlotEntityCollection);
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function prepareQuery(SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotPropelQuery): SpyConfigurableBundleTemplateSlotQuery
    {
        $configurableBundleTemplateSlotPropelQuery
            ->addJoin(SpyConfigurableBundleTemplateSlotTableMap::COL_NAME, SpyGlossaryKeyTableMap::COL_KEY, Criteria::LEFT_JOIN)
            ->addJoin(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY, SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY, Criteria::LEFT_JOIN)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION);

        return $configurableBundleTemplateSlotPropelQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot[] $configurableBundleTemplateSlotEntityCollection
     *
     * @return array
     */
    protected function mapConfigurableBundleTemplateSlots(ObjectCollection $configurableBundleTemplateSlotEntityCollection): array
    {
        $configurableBundleTemplateSlotRows = [];

        foreach ($configurableBundleTemplateSlotEntityCollection as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotRows[] = $this->mapConfigurableBundleTemplateSlotToRow($configurableBundleTemplateSlotEntity);
        }

        return $configurableBundleTemplateSlotRows;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
     *
     * @return array
     */
    protected function mapConfigurableBundleTemplateSlotToRow(SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity): array
    {
        $configurableBundleTemplateSlotRow = $configurableBundleTemplateSlotEntity->toArray();
        $configurableBundleTemplateSlotRow[static::COL_ACTIONS] = $this->buildLinks($configurableBundleTemplateSlotEntity);


        $configurableBundleTemplateSlotRow[static::COL_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_NAME_TRANSLATION] = 'hardcoded';

        return $configurableBundleTemplateSlotRow;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
     *
     * @return string
     */
    protected function buildLinks(SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity): string
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate(static::ROUTE_EDIT_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT, [
                static::URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $configurableBundleTemplateSlotEntity->getIdConfigurableBundleTemplateSlot(),
            ]),
            'Edit'
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_DELETE, [
                static::URL_PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $configurableBundleTemplateSlotEntity->getIdConfigurableBundleTemplateSlot(),
            ]),
            'Delete'
        );

        return implode(' ', $buttons);
    }
}
