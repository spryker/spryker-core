<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyProductSearchEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductConcreteHydratorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     */
    public const KEY_IS_SEARCHABLE = 'is_searchable';

    /**
     * @var string
     */
    public const KEY_SKU = 'sku';

    /**
     * @var string
     */
    public const KEY_LOCALIZED_ATTRIBUTE_TRANSFER = 'localizedAttributeTransfer';

    /**
     * @var string
     */
    public const KEY_PRODUCT_SEARCH_TRANSFER = 'productSearchEntityTransfer';

    /**
     * @var string
     */
    public const DATA_PRODUCT_CONCRETE_TRANSFER = 'DATA_PRODUCT_CONCRETE_TRANSFER';

    /**
     * @var string
     */
    public const DATA_PRODUCT_LOCALIZED_ATTRIBUTE_TRANSFER = 'DATA_PRODUCT_LOCALIZED_ATTRIBUTE_TRANSFER';

    /**
     * @var string
     */
    public const COLUMN_IS_QUANTITY_SPLITTABLE = 'is_quantity_splittable';

    /**
     * @var array<bool> Keys are product column names
     */
    protected static array $isProductColumnBuffer = [];

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $productRepository
     */
    public function __construct(protected MerchantCombinedProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        $this->importProduct($dataSet);
        $this->importProductLocalizedAttributes($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProduct(DataSetInterface $dataSet): void
    {
        $productEntityTransfer = (new SpyProductEntityTransfer())
            ->fromArray($dataSet->getArrayCopy(), true);

        $attributes = $dataSet[AttributeExtractorStep::KEY_CONCRETE_DEFAULT_ATTRIBUTES];

        $productEntityTransfer
            ->setSku($dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU])
            ->setAttributes((string)json_encode($attributes));

        $this->setIsActive($dataSet, $productEntityTransfer);

        if ($this->isProductColumn(static::COLUMN_IS_QUANTITY_SPLITTABLE)) {
            $isQuantitySplittable = (
                !isset($dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_CONCRETE_IS_QUANTITY_SPLITTABLE]) ||
                $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_CONCRETE_IS_QUANTITY_SPLITTABLE] === ''
            ) ? true : $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_CONCRETE_IS_QUANTITY_SPLITTABLE];
            $productEntityTransfer->setIsQuantitySplittable($isQuantitySplittable);
        }

        $dataSet[static::DATA_PRODUCT_CONCRETE_TRANSFER] = $productEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function importProductLocalizedAttributes(DataSetInterface $dataSet): void
    {
        $localizedAttributeTransfer = [];

        foreach ($dataSet[ProductLocalizedAttributeExtractorStep::KEY_LOCALIZED_PRODUCT_ATTRIBUTES] as $localeName => $productLocalizedAttributes) {
            $idLocale = $this->getLocaleIdByName($dataSet, $localeName);
            $localizedAttributes = $dataSet[AttributeExtractorStep::KEY_CONCRETE_LOCALIZED_ATTRIBUTES][$localeName] ?? [];
            $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];
            $idProduct = $this->productRepository->findIdProductBySku($sku);

            if (!$idProduct && !$productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_NAME]) {
                throw MerchantCombinedProductException::createWithError(
                    (new ErrorTransfer())
                        ->setMessage('Product name is required for creating a product "%s1%" with locale "%s2%".')
                        ->setParameters([
                            '%s1%' => $sku,
                            '%s2%' => $localeName,
                        ]),
                );
            }

            $productLocalizedAttributesEntityTransfer = (new SpyProductLocalizedAttributesEntityTransfer())
                ->setFkLocale($idLocale)
                ->setAttributes((string)json_encode($localizedAttributes));

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_NAME]) {
                $productLocalizedAttributesEntityTransfer
                    ->setName($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_NAME]);
            }

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_DESCRIPTION]) {
                $productLocalizedAttributesEntityTransfer
                    ->setDescription($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_DESCRIPTION]);
            }

            $productSearchEntityTransfer = (new SpyProductSearchEntityTransfer())
                ->setFkLocale($idLocale)
                ->setIsSearchable($productLocalizedAttributes[static::KEY_IS_SEARCHABLE] ?? false);

            $localizedAttributeTransfer[] = [
                static::KEY_LOCALIZED_ATTRIBUTE_TRANSFER => $productLocalizedAttributesEntityTransfer,
                static::KEY_PRODUCT_SEARCH_TRANSFER => $productSearchEntityTransfer,
                static::KEY_SKU => $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU],
            ];
        }

        $dataSet[static::DATA_PRODUCT_LOCALIZED_ATTRIBUTE_TRANSFER] = $localizedAttributeTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     *
     * @return int
     */
    protected function getLocaleIdByName(DataSetInterface $dataSet, string $localeName): int
    {
        return $dataSet[AddLocalesStep::KEY_LOCALES][$localeName];
    }

    /**
     * @param string $columnName
     *
     * @return bool
     */
    protected function isProductColumn(string $columnName): bool
    {
        if (isset(static::$isProductColumnBuffer[$columnName])) {
            return static::$isProductColumnBuffer[$columnName];
        }
        $isColumnExists = SpyProductTableMap::getTableMap()->hasColumn($columnName);
        static::$isProductColumnBuffer[$columnName] = $isColumnExists;

        return $isColumnExists;
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer $productEntityTransfer
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function setIsActive(
        DataSetInterface $dataSet,
        SpyProductEntityTransfer $productEntityTransfer
    ): void {
        $isActive = $dataSet[MerchantCombinedProductDataSetInterface::KEY_IS_ACTIVE] ?? null;
        if ($isActive !== null) {
            $productEntityTransfer->setIsActive((bool)$isActive);

            return;
        }

        /** @var string $sku */
        $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];

        $idProduct = $this->productRepository->findIdProductBySku($sku);
        if ($idProduct) {
            return;
        }

        throw MerchantCombinedProductException::createWithError(
            (new ErrorTransfer())
                ->setMessage('Expected a key "%s%" in current data set.')
                ->setParameters(['%s%' => MerchantCombinedProductDataSetInterface::KEY_IS_ACTIVE]),
        );
    }
}
