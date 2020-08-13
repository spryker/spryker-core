<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;

class ProductAbstractSkusToIdsConditionResolver implements ConditionResolverInterface
{
    protected const KEY_PRODUCT_ABSTRACT_IDS = 'productIds';
    protected const BULK_SELECT_CHUNK_SIZE = 1000;

    /**
     * @var int[]
     */
    protected $productAbstractIdsBuffer = [];

    /**
     * @param string $conditionValue
     * @param array $conditionsArray
     *
     * @return array
     */
    public function getConditions(string $conditionValue, array $conditionsArray = []): array
    {
        $productAbstractSkus = $conditionValue ? explode(',', $conditionValue) : [];
        $conditionsArray[static::KEY_PRODUCT_ABSTRACT_IDS] = $this->getProductAbstractIdsFromSkus($productAbstractSkus);

        return $conditionsArray;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return int[]
     */
    protected function getProductAbstractIdsFromSkus(array $productAbstractSkus): array
    {
        $productAbstractIds = [];

        foreach ($productAbstractSkus as $key => $productAbstractSku) {
            if (!isset($this->productAbstractIdsBuffer[$productAbstractSku])) {
                continue;
            }

            $productAbstractIds[] = $this->productAbstractIdsBuffer[$productAbstractSku];
            unset($productAbstractSkus[$key]);
        }

        if (!$productAbstractSkus) {
            return $productAbstractIds;
        }

        $productAbstractSkusChunks = array_chunk($productAbstractSkus, static::BULK_SELECT_CHUNK_SIZE);

        foreach ($productAbstractSkusChunks as $productAbstractSkusChunk) {
            $productAbstractIds = $this->getProductAbstractIdsFromDb($productAbstractSkusChunk, $productAbstractIds);
        }

        return $productAbstractIds;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param int[] $productAbstractIds
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int[]
     */
    protected function getProductAbstractIdsFromDb(array $productAbstractSkus, array $productAbstractIds): array
    {
        $productAbstractEntities = SpyProductAbstractQuery::create()
            ->filterBySku_In($productAbstractSkus)
            ->find();

        if ($productAbstractEntities->count() < count($productAbstractSkus)) {
            throw new EntityNotFoundException(
                sprintf(
                    'Could not find Product Abstract IDs by skus "%s".',
                    implode(',', $productAbstractSkus)
                )
            );
        }

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $idProductAbstract = $productAbstractEntity->getIdProductAbstract();
            $this->productAbstractIdsBuffer[$productAbstractEntity->getSku()] = $idProductAbstract;
            $productAbstractIds[] = $idProductAbstract;
        }

        return $productAbstractIds;
    }
}
