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

class CmsSlotBlockProductCategoryConditionsStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const KEY_CONDITION_PRODUCT_CATEGORY = 'productCategory';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\AllConditionResolver::KEY_ALL
     * @var string
     */
    protected const KEY_ALL = 'all';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\CategoryKeysToIdsConditionResolver::KEY_CONDITION_CATEGORY_IDS
     * @var string
     */
    protected const KEY_CONDITION_CATEGORY_IDS = 'categoryIds';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ProductAbstractSkusToIdsConditionResolver::KEY_PRODUCT_ABSTRACT_IDS
     * @var string
     */
    protected const KEY_PRODUCT_ABSTRACT_IDS = 'productIds';

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    protected $allConditionsResolver;

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    protected $productAbstractSkusToIdsConditionsResolver;

    /**
     * @var \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface
     */
    protected $categoryKeysToIdsConditionsResolver;

    /**
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface $allConditionsResolver
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface $productAbstractSkusToIdsConditionsResolver
     * @param \Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver\ConditionResolverInterface $categoryKeysToIdsConditionsResolver
     */
    public function __construct(
        ConditionResolverInterface $allConditionsResolver,
        ConditionResolverInterface $productAbstractSkusToIdsConditionsResolver,
        ConditionResolverInterface $categoryKeysToIdsConditionsResolver
    ) {
        $this->allConditionsResolver = $allConditionsResolver;
        $this->productAbstractSkusToIdsConditionsResolver = $productAbstractSkusToIdsConditionsResolver;
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

        $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY] = $this->allConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_PRODUCT_CATEGORY_ALL],
        );

        $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY] = $this->productAbstractSkusToIdsConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_PRODUCT_CATEGORY_SKUS],
            $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY],
        );

        $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY] = $this->categoryKeysToIdsConditionsResolver->getConditions(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_PRODUCT_CATEGORY_KEYS],
            $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY],
        );

        if (!array_filter($conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY])) {
            return;
        }

        if (
            $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_CATEGORY_IDS]
            || $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_PRODUCT_ABSTRACT_IDS]
        ) {
            $conditionsArray[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_ALL] = false;
        }

        $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] = $conditionsArray;
    }
}
