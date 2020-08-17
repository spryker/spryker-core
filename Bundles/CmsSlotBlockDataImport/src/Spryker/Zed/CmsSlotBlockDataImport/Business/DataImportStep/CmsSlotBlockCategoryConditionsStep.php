<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockCategoryConditionsStep implements DataImportStepInterface
{
    protected const KEY_CONDITION_CATEGORY = 'category';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\AllConditionResolver::KEY_ALL
     */
    protected const KEY_ALL = 'all';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\CategoryKeysToIdsConditionResolver::KEY_CONDITION_CATEGORY_IDS
     */
    protected const KEY_CONDITION_CATEGORY_IDS = 'categoryIds';

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    protected $allConditionsResolver;

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    protected $categoryKeysToIdsConditionsResolver;

    /**
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface $allConditionsResolver
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface $categoryKeysToIdsConditionsResolver
     */
    public function __construct(
        ConditionResolverInterface $allConditionsResolver,
        ConditionResolverInterface $categoryKeysToIdsConditionsResolver
    ) {
        $this->allConditionsResolver = $allConditionsResolver;
        $this->categoryKeysToIdsConditionsResolver = $categoryKeysToIdsConditionsResolver;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $conditionsArray = $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] ?? [];

        $conditionsArray[static::KEY_CONDITION_CATEGORY] = $this->allConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CATEGORY_ALL]
        );

        $conditionsArray[static::KEY_CONDITION_CATEGORY] = $this->categoryKeysToIdsConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_CATEGORY_KEYS],
            $conditionsArray[static::KEY_CONDITION_CATEGORY]
        );

        if (!array_filter($conditionsArray[static::KEY_CONDITION_CATEGORY])) {
            return;
        }

        if ($conditionsArray[static::KEY_CONDITION_CATEGORY][static::KEY_CONDITION_CATEGORY_IDS]) {
            $conditionsArray[static::KEY_CONDITION_CATEGORY][static::KEY_ALL] = false;
        }

        $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] = $conditionsArray;
    }
}
