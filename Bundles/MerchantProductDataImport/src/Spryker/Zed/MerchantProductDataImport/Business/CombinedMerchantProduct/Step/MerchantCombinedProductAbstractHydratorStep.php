<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyProductCategoryEntityTransfer;
use Generated\Shared\Transfer\SpyUrlEntityTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductAbstractHydratorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     */
    public const DATA_PRODUCT_ABSTRACT_TRANSFER = 'DATA_PRODUCT_ABSTRACT_TRANSFER';

    /**
     * @var string
     */
    public const DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER = 'DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFERS';

    /**
     * @var string
     */
    public const DATA_PRODUCT_CATEGORY_TRANSFER = 'DATA_PRODUCT_CATEGORY_TRANSFER';

    /**
     * @var string
     */
    public const DATA_PRODUCT_URL_TRANSFER = 'DATA_PRODUCT_URL_TRANSFER';

    /**
     * @var string
     */
    public const KEY_PRODUCT_CATEGORY_TRANSFER = 'productCategoryTransfer';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER = 'localizedAttributeTransfer';

    /**
     * @var string
     */
    public const KEY_PRODUCT_URL_TRANSFER = 'urlTransfer';

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

        $this->importProductAbstract($dataSet);
        $this->importProductAbstractLocalizedAttributes($dataSet);
        $this->importProductCategories($dataSet);
        $this->importProductUrls($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductAbstract(DataSetInterface $dataSet): void
    {
        $productAbstractEntityTransfer = (new SpyProductAbstractEntityTransfer())
            ->setSku($dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU]);

        $attributes = $dataSet[AttributeExtractorStep::KEY_ABSTRACT_DEFAULT_ATTRIBUTES];

        $productAbstractEntityTransfer
            ->fromArray($dataSet->getArrayCopy(), true)
            ->setAttributes((string)json_encode($attributes));

        $this->setTaxSetId($productAbstractEntityTransfer, $dataSet);
        $this->setNewFrom($productAbstractEntityTransfer, $dataSet);
        $this->setNewTo($productAbstractEntityTransfer, $dataSet);

        $dataSet[static::DATA_PRODUCT_ABSTRACT_TRANSFER] = $productAbstractEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function importProductAbstractLocalizedAttributes(DataSetInterface $dataSet): void
    {
        $localizedAttributeTransfers = [];

        foreach ($dataSet[ProductLocalizedAttributeExtractorStep::KEY_LOCALIZED_PRODUCT_ABSTRACT_ATTRIBUTES] as $localeName => $productLocalizedAttributes) {
            $idLocale = $this->getLocaleIdByName($dataSet, $localeName);
            $abstractSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];
            $localizedAttributes = $dataSet[AttributeExtractorStep::KEY_ABSTRACT_LOCALIZED_ATTRIBUTES][$localeName] ?? [];
            $idProductAbstract = $this->productRepository->findIdProductAbstractByAbstractSku($abstractSku);

            if (!$idProductAbstract && !$productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_NAME]) {
                throw new MerchantCombinedProductException(sprintf(
                    'The product abstract cannot be created without a name. Required column "%s" is missing',
                    MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_NAME_LOCALIZED,
                ));
            }

            $productAbstractLocalizedAttributesEntityTransfer = (new SpyProductAbstractLocalizedAttributesEntityTransfer())
                ->setFkLocale($idLocale);

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_NAME]) {
                $productAbstractLocalizedAttributesEntityTransfer
                    ->setName($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_NAME]);
            }

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_DESCRIPTION]) {
                $productAbstractLocalizedAttributesEntityTransfer
                    ->setDescription($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_DESCRIPTION]);
            }

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_META_TITLE]) {
                $productAbstractLocalizedAttributesEntityTransfer
                    ->setMetaTitle($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_META_TITLE]);
            }

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_META_DESCRIPTION]) {
                $productAbstractLocalizedAttributesEntityTransfer
                    ->setMetaDescription($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_META_DESCRIPTION]);
            }

            if ($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_META_KEYWORDS]) {
                $productAbstractLocalizedAttributesEntityTransfer
                    ->setMetaKeywords($productLocalizedAttributes[ProductLocalizedAttributeExtractorStep::KEY_META_KEYWORDS]);
            }

            $productAbstractLocalizedAttributesEntityTransfer
                ->setAttributes((string)json_encode($localizedAttributes));

            $localizedAttributeTransfers[] = [
                MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU => $abstractSku,
                static::KEY_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER => $productAbstractLocalizedAttributesEntityTransfer,
            ];
        }

        $dataSet[static::DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] = $localizedAttributeTransfers;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function importProductCategories(DataSetInterface $dataSet): void
    {
        $productCategoryTransfers = [];
        $categoryToIndexMap = $dataSet[CategoryExtractorStep::KEY_CATEGORY_TO_ORDER_MAP];
        $categories = $this->getCategories($dataSet);

        foreach ($categoryToIndexMap as $categoryKey => $index) {
            if (!isset($categories[$categoryKey])) {
                throw new MerchantCombinedProductException(sprintf(
                    'The category with key "%s" was not found in existing categories list.',
                    $categoryKey,
                ));
            }

            $productCategoryEntityTransfer = (new SpyProductCategoryEntityTransfer())
                ->setFkCategory($categories[$categoryKey]);

            if ($index) {
                $productCategoryEntityTransfer->setProductOrder((int)$index);
            }

            $productCategoryTransfers[] = [
                MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU => $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU],
                static::KEY_PRODUCT_CATEGORY_TRANSFER => $productCategoryEntityTransfer,
            ];
        }

        $dataSet[static::DATA_PRODUCT_CATEGORY_TRANSFER] = $productCategoryTransfers;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function importProductUrls(DataSetInterface $dataSet): void
    {
        $urlsTransfer = [];

        foreach ($dataSet[LocalizedUrlExtractorStep::KEY_LOCALIZED_URLS] as $localeName => $abstractProductUrl) {
            $idLocale = $this->getLocaleIdByName($dataSet, $localeName);

            if ($this->isNewProductAbstract($dataSet) && !$abstractProductUrl) {
                $dataSetKey = strtr(
                    MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_URL_LOCALIZED,
                    [
                        MerchantCombinedProductDataSetInterface::PLACEHOLDER_LOCALE => $localeName,
                    ],
                );

                throw new MerchantCombinedProductException(sprintf(
                    'The product abstract cannot be created without a URL. Required column "%s" is missing',
                    $dataSetKey,
                ));
            }

            $urlEntityTransfer = (new SpyUrlEntityTransfer())
                ->setFkLocale($idLocale)
                ->setUrl($abstractProductUrl);

            $urlsTransfer[] = [
                MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU => $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU],
                static::KEY_PRODUCT_URL_TRANSFER => $urlEntityTransfer,
            ];
        }

        $dataSet[static::DATA_PRODUCT_URL_TRANSFER] = $urlsTransfer;
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
     * @param \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer $productAbstractEntityTransfer
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function setTaxSetId(
        SpyProductAbstractEntityTransfer $productAbstractEntityTransfer,
        DataSetInterface $dataSet
    ): void {
        $taxSetId = $dataSet[AddTaxSetIdStep::KEY_ID_TAX_SET] ?? null;

        if ($taxSetId === null) {
            return;
        }

        $productAbstractEntityTransfer->setFkTaxSet($taxSetId);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer $productAbstractEntityTransfer
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function setNewFrom(
        SpyProductAbstractEntityTransfer $productAbstractEntityTransfer,
        DataSetInterface $dataSet
    ): void {
        $newFrom = $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_NEW_FROM] ?? null;

        if ($newFrom === null) {
            return;
        }

        $productAbstractEntityTransfer->setNewFrom($newFrom);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer $productAbstractEntityTransfer
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function setNewTo(
        SpyProductAbstractEntityTransfer $productAbstractEntityTransfer,
        DataSetInterface $dataSet
    ): void {
        $newTo = $dataSet[MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_NEW_TO] ?? null;

        if ($newTo === null) {
            return;
        }

        $productAbstractEntityTransfer->setNewTo($newTo);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isNewProductAbstract(DataSetInterface $dataSet): bool
    {
        return $dataSet[DefineIsNewProductStep::DATA_KEY_IS_NEW_PRODUCT_ABSTRACT];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, int>
     */
    protected function getCategories(DataSetInterface $dataSet): array
    {
        return $dataSet[AddProductCategoryKeysStep::KEY_CATEGORY_KEYS] ?? [];
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
        ];
    }
}
