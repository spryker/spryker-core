<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AddAttributeKeysStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_ATTRIBUTE_KEYS = 'ATTRIBUTE_KEYS';

    /**
     * @var array<string>
     */
    protected array $attributeKeys = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->attributeKeys) {
            $attributeKeys = SpyProductAttributeKeyQuery::create()
                ->select(SpyProductAttributeKeyTableMap::COL_KEY)
                ->find()
                ->getData();

            $this->attributeKeys = $attributeKeys;
        }

        $dataSet[static::KEY_ATTRIBUTE_KEYS] = $this->attributeKeys;
    }
}
