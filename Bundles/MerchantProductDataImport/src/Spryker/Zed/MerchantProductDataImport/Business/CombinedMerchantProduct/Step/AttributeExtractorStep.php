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

class AttributeExtractorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    public const ATTRIBUTE_MULTI_VALUE_DATASET_DELIMITER = ';';

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    public const ATTRIBUTE_MULTI_VALUE_DELIMITER = ',';

    /**
     * @var string
     */
    public const KEY_CONCRETE_DEFAULT_ATTRIBUTES = 'CONCRETE_DEFAULT_ATTRIBUTES';

    /**
     * @var string
     */
    public const KEY_CONCRETE_LOCALIZED_ATTRIBUTES = 'CONCRETE_LOCALIZED_ATTRIBUTES';

    /**
     * @var string
     */
    public const KEY_ABSTRACT_DEFAULT_ATTRIBUTES = 'ABSTRACT_DEFAULT_ATTRIBUTES';

    /**
     * @var string
     */
    public const KEY_ABSTRACT_LOCALIZED_ATTRIBUTES = 'ABSTRACT_LOCALIZED_ATTRIBUTES';

    /**
     * @var array<string, string>
     */
    protected array $dataSetConcreteDefaultAttributeKeys = [];

    /**
     * @var array<string, array<string, string>>
     */
    protected array $dataSetConcreteLocalizedAttributeKeys = [];

    /**
     * @var array<string, string>
     */
    protected array $dataSetAbstractDefaultAttributeKeys = [];

    /**
     * @var array<string, array<string, string>>
     */
    protected array $dataSetAbstractLocalizedAttributeKeys = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[static::KEY_ABSTRACT_DEFAULT_ATTRIBUTES] = $this->collectAttributes(
            $dataSet,
            $this->getAbstractDefaultAttributesDataSetKeys($dataSet),
        );
        $dataSet[static::KEY_ABSTRACT_LOCALIZED_ATTRIBUTES] = $this->collectLocalizedAttributes(
            $dataSet,
            $this->getAbstractLocalizedAttributesDataSetKeys($dataSet),
        );
        $dataSet[static::KEY_CONCRETE_DEFAULT_ATTRIBUTES] = $this->collectAttributes(
            $dataSet,
            $this->getConcreteDefaultAttributesDataSetKeys($dataSet),
        );
        $dataSet[static::KEY_CONCRETE_LOCALIZED_ATTRIBUTES] = $this->collectLocalizedAttributes(
            $dataSet,
            $this->getConcreteLocalizedAttributesDataSetKeys($dataSet),
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param array<string, string> $dataSetAttributeKeysMap
     *
     * @return array<string, mixed>
     */
    protected function collectAttributes(
        DataSetInterface $dataSet,
        array $dataSetAttributeKeysMap
    ): array {
        $attributes = [];
        foreach ($dataSetAttributeKeysMap as $dataSetKey => $attributeKey) {
            $attributeValue = $dataSet[$dataSetKey] ?? null;
            if (!$attributeValue) {
                continue;
            }

            if ($this->isMultiValueAttribute($attributeValue)) {
                $attributeValue = explode(static::ATTRIBUTE_MULTI_VALUE_DATASET_DELIMITER, $attributeValue);
                $attributeValue = implode(static::ATTRIBUTE_MULTI_VALUE_DELIMITER, $attributeValue);
            }

            $attributes[$attributeKey] = $attributeValue;
        }

        return array_filter($attributes);
    }

    /**
     * @param string $attributeValue
     *
     * @return bool
     */
    protected function isMultiValueAttribute(string $attributeValue): bool
    {
        return str_contains($attributeValue, static::ATTRIBUTE_MULTI_VALUE_DATASET_DELIMITER);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param array $dataSetKeys
     *
     * @return array<string, array<string, mixed>>
     */
    protected function collectLocalizedAttributes(
        DataSetInterface $dataSet,
        array $dataSetKeys
    ): array {
        $localizedAttributes = [];
        foreach ($dataSetKeys as $localeName => $dataSetAttributeKeysMap) {
            $localizedAttributes[$localeName] = $this->collectAttributes(
                $dataSet,
                $dataSetAttributeKeysMap,
            );
        }

        return $localizedAttributes;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, string>
     */
    protected function getConcreteDefaultAttributesDataSetKeys(DataSetInterface $dataSet): array
    {
        if (!$this->isProductConcreteSupported($dataSet)) {
            return [];
        }

        if (!$this->dataSetConcreteDefaultAttributeKeys) {
            $supportedAttributeKeys = $this->getSupportedAttributeKeys($dataSet);
            $dataSet = $dataSet->getArrayCopy();
            foreach ($supportedAttributeKeys as $attributeKey) {
                $dataSetKey = $this->formatDefaultDataSetAttributeKey(
                    MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ATTRIBUTE_KEY_PATTERN,
                    $attributeKey,
                );
                if (!array_key_exists($dataSetKey, $dataSet)) {
                    continue;
                }

                $this->dataSetConcreteDefaultAttributeKeys[$dataSetKey] = $attributeKey;
            }
        }

        return $this->dataSetConcreteDefaultAttributeKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, array<string, string>>
     */
    protected function getConcreteLocalizedAttributesDataSetKeys(DataSetInterface $dataSet): array
    {
        if (!$this->isProductConcreteSupported($dataSet)) {
            return [];
        }

        if (!$this->dataSetConcreteLocalizedAttributeKeys) {
            $localeNames = $this->getLocaleNames($dataSet);
            $supportedAttributeKeys = $this->getSupportedAttributeKeys($dataSet);
            $dataSetArray = $dataSet->getArrayCopy();

            foreach ($supportedAttributeKeys as $attributeKey) {
                foreach ($localeNames as $localeName) {
                    $dataSetKey = $this->formatLocalizedDataSetAttributeKey(
                        MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ATTRIBUTE_KEY_LOCALIZED,
                        $attributeKey,
                        $localeName,
                    );
                    if (!array_key_exists($dataSetKey, $dataSetArray)) {
                        continue;
                    }

                    $this->dataSetConcreteLocalizedAttributeKeys[$localeName][$dataSetKey] = $attributeKey;
                }
            }
        }

        return $this->dataSetConcreteLocalizedAttributeKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, string>
     */
    protected function getAbstractDefaultAttributesDataSetKeys(DataSetInterface $dataSet): array
    {
        if (!$this->isProductAbstractSupported($dataSet)) {
            return [];
        }

        if (!$this->dataSetAbstractDefaultAttributeKeys) {
            $supportedAttributeKeys = $this->getSupportedAttributeKeys($dataSet);
            $dataSet = $dataSet->getArrayCopy();
            foreach ($supportedAttributeKeys as $attributeKey) {
                $dataSetKey = $this->formatDefaultDataSetAttributeKey(
                    MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_ATTRIBUTE_KEY_PATTERN,
                    $attributeKey,
                );
                if (!array_key_exists($dataSetKey, $dataSet)) {
                    continue;
                }

                $this->dataSetAbstractDefaultAttributeKeys[$dataSetKey] = $attributeKey;
            }
        }

        return $this->dataSetAbstractDefaultAttributeKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, array<string, string>>
     */
    protected function getAbstractLocalizedAttributesDataSetKeys(DataSetInterface $dataSet): array
    {
        if (!$this->isProductAbstractSupported($dataSet)) {
            return [];
        }

        if (!$this->dataSetAbstractLocalizedAttributeKeys) {
            $localeNames = $this->getLocaleNames($dataSet);
            $supportedAttributeKeys = $this->getSupportedAttributeKeys($dataSet);
            $dataSetArray = $dataSet->getArrayCopy();

            foreach ($supportedAttributeKeys as $attributeKey) {
                foreach ($localeNames as $localeName) {
                    $dataSetKey = $this->formatLocalizedDataSetAttributeKey(
                        MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_ATTRIBUTE_KEY_LOCALIZED,
                        $attributeKey,
                        $localeName,
                    );
                    if (!array_key_exists($dataSetKey, $dataSetArray)) {
                        continue;
                    }

                    $this->dataSetAbstractLocalizedAttributeKeys[$localeName][$dataSetKey] = $attributeKey;
                }
            }
        }

        return $this->dataSetAbstractLocalizedAttributeKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string>
     */
    protected function getLocaleNames(DataSetInterface $dataSet): array
    {
        /** @var array<string> $localeNames */
        $localeNames = array_keys($dataSet[AddLocalesStep::KEY_LOCALES]);

        return $localeNames;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string>
     */
    protected function getSupportedAttributeKeys(DataSetInterface $dataSet): array
    {
        return $dataSet[AddAttributeKeysStep::KEY_ATTRIBUTE_KEYS];
    }

    /**
     * @param string $pattern
     * @param string $attributeKey
     *
     * @return string
     */
    protected function formatDefaultDataSetAttributeKey(string $pattern, string $attributeKey): string
    {
        return strtr(
            $pattern,
            [
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_ATTRIBUTE_KEY => $attributeKey,
            ],
        );
    }

    /**
     * @param string $pattern
     * @param string $attributeKey
     * @param string $localeName
     *
     * @return string
     */
    protected function formatLocalizedDataSetAttributeKey(
        string $pattern,
        string $attributeKey,
        string $localeName
    ): string {
        return strtr(
            $pattern,
            [
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_ATTRIBUTE_KEY => $attributeKey,
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_LOCALE => $localeName,
            ],
        );
    }
}
