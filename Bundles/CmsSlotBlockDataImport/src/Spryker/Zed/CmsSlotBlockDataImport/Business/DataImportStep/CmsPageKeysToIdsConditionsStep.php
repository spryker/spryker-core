<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPageKeysToIdsConditionsStep implements DataImportStepInterface
{
    protected const KEY_CONDITION_CMS_PAGE = 'cms_page';

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface
     */
    protected $allConditionsResolver;

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface
     */
    protected $cmsPageKeysToIdsConditionsResolver;

    /**
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface $allConditionsResolver
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface $categoryKeysToIdsConditionsResolver
     */
    public function __construct(
        ConditionsResolverInterface $allConditionsResolver,
        ConditionsResolverInterface $categoryKeysToIdsConditionsResolver
    ) {
        $this->allConditionsResolver = $allConditionsResolver;
        $this->cmsPageKeysToIdsConditionsResolver = $categoryKeysToIdsConditionsResolver;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $conditionsArray = $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] ?? [];

        $conditionsArray[static::KEY_CONDITION_CMS_PAGE] = $this->allConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CMS_PAGE_ALL]
        );

        if ($dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CMS_PAGE_KEYS]) {
            $conditionsArray[static::KEY_CONDITION_CMS_PAGE] = $this->cmsPageKeysToIdsConditionsResolver->getConditions(
                $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CMS_PAGE_KEYS],
                $conditionsArray[static::KEY_CONDITION_CMS_PAGE]
            );
        }

        if (!$conditionsArray[static::KEY_CONDITION_CMS_PAGE]) {
            return;
        }

        $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] = $conditionsArray;
    }
}
