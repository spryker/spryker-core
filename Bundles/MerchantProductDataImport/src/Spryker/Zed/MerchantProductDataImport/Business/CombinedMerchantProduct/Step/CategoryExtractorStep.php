<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;

class CategoryExtractorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_CATEGORY_TO_ORDER_MAP = 'CATEGORY_TO_ORDER_MAP';

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    protected const DELIMITER_CATEGORIES = ';';

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    protected const DELIMITER_ORDER = ':';

    /**
     * @var int
     */
    protected const DEFAULT_CATEGORY_ORDER = 0;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $categoriesToOrderMap = $this->buildCategoryToOrderMap($dataSet);

        $dataSet[static::KEY_CATEGORY_TO_ORDER_MAP] = $categoriesToOrderMap;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, int> keys are category keys, values are category order numbers.
     */
    protected function buildCategoryToOrderMap(DataSetInterface $dataSet): array
    {
        if (
            !isset($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_CATEGORIES])
            || empty($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_CATEGORIES])
        ) {
            return [];
        }

        $this->assertCategoriesSeparator(
            $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_CATEGORIES],
        );

        $categories = array_filter(explode(
            static::DELIMITER_CATEGORIES,
            $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_CATEGORIES],
        ));

        $categoriesOrderMap = [];
        foreach ($categories as $categoryData) {
            $categoriesOrderParts = explode(static::DELIMITER_ORDER, $categoryData);
            $categoriesOrderMap[$categoriesOrderParts[0]] = isset($categoriesOrderParts[1])
                ? (int)$categoriesOrderParts[1]
                : static::DEFAULT_CATEGORY_ORDER;
        }

        return $categoriesOrderMap;
    }

    /**
     * @param string $categories
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertCategoriesSeparator(string $categories): void
    {
        if (!str_contains($categories, ',')) {
            return;
        }

        throw MerchantCombinedProductException::createWithError(
            (new ErrorTransfer())
                ->setMessage('Product abstract categories must be delimited by "%s%" instead of "","".')
                ->setParameters(['%s%' => static::DELIMITER_CATEGORIES]),
        );
    }
}
