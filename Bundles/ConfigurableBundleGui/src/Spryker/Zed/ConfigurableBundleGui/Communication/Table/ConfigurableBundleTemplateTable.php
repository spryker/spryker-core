<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Table;

use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateSlotTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class ConfigurableBundleTemplateTable extends AbstractTable
{
    protected const STATUS_ACTIVE = 'Active';
    protected const STATUS_INACTIVE = 'Inactive';

    protected const COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id_configurable_bundle_template';
    protected const COL_NAME_TRANSLATION = 'name_translation';
    protected const COL_COUNT_OF_SLOTS = 'count_of_slots';
    protected const COL_STATUS = 'is_active';
    protected const COL_ACTIONS = 'actions';

    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'id-configurable-bundle-template';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::editAction()
     */
    protected const ROUTE_EDIT_CONFIGURABLE_BUNDLE_TEMPLATE = '/configurable-bundle-gui/template/edit';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::activateAction()
     */
    protected const ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_ACTIVATE = '/configurable-bundle-gui/template/activate';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::deactivateAction()
     */
    protected const ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_DEACTIVATE = '/configurable-bundle-gui/template/deactivate';

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\TemplateController::confirmDeleteAction()
     */
    protected const ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_CONFIRM_DELETE = '/configurable-bundle-gui/template/confirm-delete';

    /**
     * @var \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected $configurableBundleTemplatePropelQuery;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery $configurableBundleTemplatePropelQuery
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SpyConfigurableBundleTemplateQuery $configurableBundleTemplatePropelQuery,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->configurableBundleTemplatePropelQuery = $configurableBundleTemplatePropelQuery;
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
            static::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE => 'ID',
            static::COL_NAME_TRANSLATION => 'Name',
            static::COL_COUNT_OF_SLOTS => '# of Slots',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE,
            static::COL_NAME_TRANSLATION,
            static::COL_COUNT_OF_SLOTS,
            static::COL_STATUS,
        ]);

        $config->setSearchable([
            SpyConfigurableBundleTemplateTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE,
            SpyGlossaryTranslationTableMap::COL_VALUE,
            sprintf('COUNT(%s)', SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT),
        ]);

        $config->setHasSearchableFieldsWithAggregateFunctions(true);

        $config->setRawColumns([
            static::COL_STATUS,
            static::COL_ACTIONS,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate[] $configurableBundleTemplateEntityCollection */
        $configurableBundleTemplateEntityCollection = $this->runQuery(
            $this->prepareQuery($this->configurableBundleTemplatePropelQuery),
            $config,
            true
        );

        if ($configurableBundleTemplateEntityCollection->count() === 0) {
            return [];
        }

        return $this->mapConfigurableBundleTemplates($configurableBundleTemplateEntityCollection);
    }

    /**
     * @module ConfigurableBundle
     * @module Glossary
     *
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery $configurableBundleTemplatePropelQuery
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function prepareQuery(SpyConfigurableBundleTemplateQuery $configurableBundleTemplatePropelQuery): SpyConfigurableBundleTemplateQuery
    {
        $configurableBundleTemplatePropelQuery
            ->leftJoinSpyConfigurableBundleTemplateSlot()
            ->addJoin(SpyConfigurableBundleTemplateTableMap::COL_NAME, SpyGlossaryKeyTableMap::COL_KEY, Criteria::LEFT_JOIN)
            ->addJoin(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY, SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY, Criteria::LEFT_JOIN)
            ->withColumn(
                sprintf('COUNT(%s)', SpyConfigurableBundleTemplateSlotTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT),
                static::COL_COUNT_OF_SLOTS
            )
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::COL_NAME_TRANSLATION)
            ->where(
                sprintf(
                    '%s = %s',
                    SpyGlossaryTranslationTableMap::COL_FK_LOCALE,
                    $this->localeFacade->getCurrentLocale()
                        ->getIdLocale()
                )
            )
            ->groupByIdConfigurableBundleTemplate();

        return $configurableBundleTemplatePropelQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate[] $configurableBundleTemplateEntityCollection
     *
     * @return array
     */
    protected function mapConfigurableBundleTemplates(ObjectCollection $configurableBundleTemplateEntityCollection): array
    {
        $configurableBundleTemplateRow = [];

        foreach ($configurableBundleTemplateEntityCollection as $configurableBundleTemplateEntity) {
            $configurableBundleTemplateRow[] = $this->mapConfigurableBundleTemplate($configurableBundleTemplateEntity);
        }

        return $configurableBundleTemplateRow;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return array
     */
    protected function mapConfigurableBundleTemplate(SpyConfigurableBundleTemplate $configurableBundleTemplateEntity): array
    {
        $configurableBundleTemplateRow = $configurableBundleTemplateEntity->toArray();

        $configurableBundleTemplateRow[static::COL_ACTIONS] = $this->buildLinks($configurableBundleTemplateEntity);
        $configurableBundleTemplateRow[static::COL_STATUS] = $this->getStatusLabel($configurableBundleTemplateEntity);

        return $configurableBundleTemplateRow;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return string
     */
    protected function buildLinks(SpyConfigurableBundleTemplate $configurableBundleTemplateEntity): string
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(static::ROUTE_EDIT_CONFIGURABLE_BUNDLE_TEMPLATE, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate(),
            ]),
            'Edit'
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_CONFIRM_DELETE, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate(),
            ]),
            'Delete'
        );

        $buttons[] = $this->generateTemplateStatusChangeButton($configurableBundleTemplateEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return string
     */
    protected function generateTemplateStatusChangeButton(SpyConfigurableBundleTemplate $configurableBundleTemplateEntity): string
    {
        if ($configurableBundleTemplateEntity->getIsActive()) {
            return $this->generateRemoveButton(
                Url::generate(static::ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_DEACTIVATE, [
                    static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate(),
                ]),
                'Deactivate'
            );
        }

        return $this->generateViewButton(
            Url::generate(static::ROUTE_CONFIGURABLE_BUNDLE_TEMPLATE_ACTIVATE, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate(),
            ]),
            'Activate'
        );
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return string
     */
    protected function getStatusLabel(SpyConfigurableBundleTemplate $configurableBundleTemplateEntity): string
    {
        if (!$configurableBundleTemplateEntity->getIsActive()) {
            return $this->generateLabel(static::STATUS_INACTIVE, 'label-danger');
        }

        return $this->generateLabel(static::STATUS_ACTIVE, 'label-info');
    }
}
