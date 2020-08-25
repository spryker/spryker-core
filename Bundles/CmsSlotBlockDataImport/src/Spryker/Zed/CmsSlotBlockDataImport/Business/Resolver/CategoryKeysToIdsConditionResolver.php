<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver;

use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;

class CategoryKeysToIdsConditionResolver implements ConditionResolverInterface
{
    protected const KEY_CONDITION_CATEGORY_IDS = 'categoryIds';

    /**
     * @var int[]
     */
    protected $categoryIdsBuffer = [];

    /**
     * @param string $conditionValue
     * @param array $conditionsArray
     *
     * @return array
     */
    public function getConditions(string $conditionValue, array $conditionsArray = []): array
    {
        $categoryKeys = $conditionValue ? explode(',', $conditionValue) : [];
        $conditionsArray[static::KEY_CONDITION_CATEGORY_IDS] = $this->getCategoryIdsFromKeys($categoryKeys);

        return $conditionsArray;
    }

    /**
     * @param string[] $categoryKeys
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int[]
     */
    protected function getCategoryIdsFromKeys(array $categoryKeys): array
    {
        $categoryIds = [];

        foreach ($categoryKeys as $key => $categoryKey) {
            if (!isset($this->categoryIdsBuffer[$categoryKey])) {
                continue;
            }

            $categoryIds[] = $this->categoryIdsBuffer[$categoryKey];
            unset($categoryKeys[$key]);
        }

        if (!$categoryKeys) {
            return $categoryIds;
        }

        $categoryEntities = SpyCategoryQuery::create()
            ->filterByCategoryKey_In($categoryKeys)
            ->find();

        if ($categoryEntities->count() < count($categoryKeys)) {
            throw new EntityNotFoundException(
                sprintf(
                    'Could not find Category IDs by Keys "%s".',
                    implode(',', $categoryKeys)
                )
            );
        }

        foreach ($categoryEntities as $categoryEntity) {
            $idCategory = $categoryEntity->getIdCategory();
            $this->categoryIdsBuffer[$categoryEntity->getCategoryKey()] = $idCategory;
            $categoryIds[] = $idCategory;
        }

        return $categoryIds;
    }
}
