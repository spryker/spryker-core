<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AddProductCategoryKeysStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_CATEGORY_KEYS = 'CATEGORY_KEYS';

    /**
     * @var array<string, int> Keys are category keys, values are category ids
     */
    protected array $categoryKeys = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->categoryKeys) {
            /** @var array<\Orm\Zed\Category\Persistence\SpyCategory> $categoryEntityCollection */
            $categoryEntityCollection = SpyCategoryQuery::create()->find();

            foreach ($categoryEntityCollection as $categoryEntity) {
                $this->categoryKeys[$categoryEntity->getCategoryKey()] = $categoryEntity->getIdCategory();
            }
        }

        $dataSet[static::KEY_CATEGORY_KEYS] = $this->categoryKeys;
    }
}
