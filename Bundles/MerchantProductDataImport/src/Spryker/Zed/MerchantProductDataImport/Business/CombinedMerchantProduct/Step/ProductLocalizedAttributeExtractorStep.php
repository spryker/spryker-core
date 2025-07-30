<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;

class ProductLocalizedAttributeExtractorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_LOCALIZED_PRODUCT_ATTRIBUTES = 'LOCALIZED_PRODUCT_ATTRIBUTES';

    /**
     * @var string
     */
    public const KEY_LOCALIZED_PRODUCT_ABSTRACT_ATTRIBUTES = 'LOCALIZED_PRODUCT_ABSTRACT_ATTRIBUTES';

    /**
     * @var string
     */
    public const KEY_NAME = 'name';

    /**
     * @var string
     */
    public const KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const KEY_META_TITLE = 'meta_title';

    /**
     * @var string
     */
    public const KEY_META_DESCRIPTION = 'meta_description';

    /**
     * @var string
     */
    public const KEY_META_KEYWORDS = 'meta_keywords';

    /**
     * @var string
     */
    public const KEY_IS_SEARCHABLE = 'is_searchable';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->extractProductAbstractLocalizedAttributes($dataSet);
        $this->extractProductLocalizedAttributes($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function extractProductLocalizedAttributes(DataSetInterface $dataSet): void
    {
        $localeNames = $this->getLocaleNames($dataSet);

        $localizedProductAttributes = [];
        foreach ($localeNames as $localeName) {
            $localizedProductAttributes[$localeName] = $this->collectProductAttributesForLocale($dataSet, $localeName);
        }

        $dataSet[static::KEY_LOCALIZED_PRODUCT_ATTRIBUTES] = $localizedProductAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function extractProductAbstractLocalizedAttributes(DataSetInterface $dataSet): void
    {
        $localeNames = $this->getLocaleNames($dataSet);

        $localizedProductAbstractAttributes = [];
        foreach ($localeNames as $localeName) {
            $localizedProductAbstractAttributes[$localeName] = $this->collectProductAbstractAttributesForLocale(
                $dataSet,
                $localeName,
            );
        }

        $dataSet[static::KEY_LOCALIZED_PRODUCT_ABSTRACT_ATTRIBUTES] = $localizedProductAbstractAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    protected function collectProductAbstractAttributesForLocale(DataSetInterface $dataSet, string $localeName): array
    {
        $productAbstractAttributes = [];

        $productAbstractAttributes[static::KEY_NAME] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_NAME_LOCALIZED, $localeName);
        $productAbstractAttributes[static::KEY_DESCRIPTION] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_DESCRIPTION_LOCALIZED, $localeName);
        $productAbstractAttributes[static::KEY_META_TITLE] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_META_TITLE_LOCALIZED, $localeName);
        $productAbstractAttributes[static::KEY_META_DESCRIPTION] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_META_DESCRIPTION_LOCALIZED, $localeName);
        $productAbstractAttributes[static::KEY_META_KEYWORDS] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_META_KEYWORDS_LOCALIZED, $localeName);

        return $productAbstractAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     *
     * @return array<string, string|null>
     */
    protected function collectProductAttributesForLocale(DataSetInterface $dataSet, string $localeName): array
    {
        $productAttributes = [];

        $productAttributes[static::KEY_NAME] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_NAME_LOCALIZED, $localeName);
        $productAttributes[static::KEY_DESCRIPTION] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_DESCRIPTION_LOCALIZED, $localeName);
        $productAttributes[static::KEY_IS_SEARCHABLE] = $this->getLocalizedProductAttributeValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_IS_SEARCHABLE_LOCALIZED, $localeName);

        return $productAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $keyPattern
     * @param string $localeName
     *
     * @return string|null
     */
    protected function getLocalizedProductAttributeValue(
        DataSetInterface $dataSet,
        string $keyPattern,
        string $localeName
    ): ?string {
        $dataSetKey = $this->buildLocalizedKey($keyPattern, $localeName);

        return $dataSet[$dataSetKey] ?? null;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string>
     */
    protected function getLocaleNames(DataSetInterface $dataSet): array
    {
        /** @phpstan-var array<string> $localeNames */
        $localeNames = array_keys($dataSet[AddLocalesStep::KEY_LOCALES]);

        return $localeNames;
    }

    /**
     * @param string $dataSetKeyPattern
     * @param string $localeName
     *
     * @return string
     */
    protected function buildLocalizedKey(string $dataSetKeyPattern, string $localeName): string
    {
        return strtr(
            $dataSetKeyPattern,
            [
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_LOCALE => $localeName,
            ],
        );
    }
}
