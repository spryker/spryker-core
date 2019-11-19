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

class CmsSlotBlockCategoryConditionsStep implements DataImportStepInterface
{
    protected const KEY_CONDITION_CATEGORY = 'category';

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface
     */
    protected $allConditionsResolver;

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface
     */
    protected $categoryKeysToIdsConditionsResolver;

    /**
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface $allConditionsResolver
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionsResolverInterface $categoryKeysToIdsConditionsResolver
     */
    public function __construct(
        ConditionsResolverInterface $allConditionsResolver,
        ConditionsResolverInterface $categoryKeysToIdsConditionsResolver
    ) {
        $this->allConditionsResolver = $allConditionsResolver;
        $this->categoryKeysToIdsConditionsResolver = $categoryKeysToIdsConditionsResolver;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $conditionsArray = $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] ?? [];

        $conditionsArray[static::KEY_CONDITION_CATEGORY] = $this->allConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CATEGORY_ALL]
        );

        if ($dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CATEGORY_KEYS]) {
            $conditionsArray[static::KEY_CONDITION_CATEGORY] = $this->categoryKeysToIdsConditionsResolver->getConditions(
                $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CATEGORY_KEYS],
                $conditionsArray[static::KEY_CONDITION_CATEGORY]
            );
        }

        if (!$conditionsArray[static::KEY_CONDITION_CATEGORY]) {
            return;
        }

        $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] = $conditionsArray;
    }
}
