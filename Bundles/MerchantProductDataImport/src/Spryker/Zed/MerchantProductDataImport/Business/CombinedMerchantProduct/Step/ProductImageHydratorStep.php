<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;

class ProductImageHydratorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     */
    protected const CONCRETE_PRODUCT_IMAGES_KEY_PATTERN = MerchantCombinedProductDataSetInterface::KEY_PRODUCT_IMAGE_LOCALE_IMAGE_SET_NAME_COLUMN;

    /**
     * @var string
     */
    protected const ABSTRACT_PRODUCT_IMAGES_KEY_PATTERN = MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_PRODUCT_IMAGE_LOCALE_IMAGE_SET_NAME_COLUMN;

    /**
     * @var array<string>
     */
    protected const SUPPORTED_PRODUCT_IMAGE_PROPERTIES = [
        'external_url_large',
        'external_url_small',
        'sort_order',
    ];

    /**
     * @var string
     */
    protected const KEY_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const KEY_IMAGE_SET_NAME = 'image_set_name';

    /**
     * @var string
     */
    protected const KEY_PROPERTY = 'property';

    /**
     * @var string
     */
    protected const LOCALE_DEFAULT = 'default';

    /**
     * @var string
     */
    public const DATA_ABSTRACT_PRODUCT_IMAGE_SET_TRANSFERS = 'DATA_ABSTRACT_PRODUCT_IMAGE_SET_TRANSFERS';

    /**
     * @var string
     */
    public const DATA_CONCRETE_PRODUCT_IMAGE_SET_TRANSFERS = 'DATA_CONCRETE_PRODUCT_IMAGE_SET_TRANSFERS';

    /**
     * Specification:
     * - $abstractProductImageKeys[locale][image_set_name][property]
     *
     * @var array<string, array<string, array<string, string>>>
     */
    protected array $abstractProductImageKeys = [];

    /**
     * Specification:
     *  - $concreteProductImageKeys[locale][image_set_name][property]
     *
     * @var array<string, array<string, array<string, string>>>
     */
    protected array $concreteProductImageKeys = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[static::DATA_ABSTRACT_PRODUCT_IMAGE_SET_TRANSFERS] = $this->getHydratedAbstractProductImageSets($dataSet);
        $dataSet[static::DATA_CONCRETE_PRODUCT_IMAGE_SET_TRANSFERS] = $this->getHydratedConcreteProductImageSets($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function getHydratedAbstractProductImageSets(DataSetInterface $dataSet): array
    {
        if (!$this->isProductAbstractSupported($dataSet)) {
            return [];
        }

        return $this->collectHydratedProductImageSetsByKeyMap(
            $dataSet,
            $this->getAbstractProductImageKeysMap($dataSet),
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function getHydratedConcreteProductImageSets(DataSetInterface $dataSet): array
    {
        if (!$this->isProductConcreteSupported($dataSet)) {
            return [];
        }

        return $this->collectHydratedProductImageSetsByKeyMap(
            $dataSet,
            $this->getConcreteProductImageKeysMap($dataSet),
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param array<string, array<string, array<string, string>>> $localeImageSetPropertyKeyMap
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    protected function collectHydratedProductImageSetsByKeyMap(
        DataSetInterface $dataSet,
        array $localeImageSetPropertyKeyMap
    ): array {
        $productImageSetTransfers = [];
        foreach ($localeImageSetPropertyKeyMap as $localeName => $imageSetPropertyKeyMap) {
            $idLocale = $this->getLocaleIdByName($dataSet, $localeName);
            foreach ($imageSetPropertyKeyMap as $imageSetName => $propertyKeyMap) {
                $productImageSetTransfer = (new ProductImageSetTransfer())
                    ->setName($imageSetName);
                if ($idLocale) {
                    $productImageSetTransfer->setLocale(
                        (new LocaleTransfer())
                            ->setName($localeName)
                            ->setIdLocale($idLocale),
                    );
                }
                $productImageData = [];
                foreach ($propertyKeyMap as $property => $key) {
                    $productImageData[$property] = $this->getDataSetPropertyOrFail($dataSet, $key);
                }

                $productImageTransfer = (new ProductImageTransfer())
                    ->fromArray($productImageData, true);

                if (!$productImageTransfer->getSortOrder()) {
                    $productImageTransfer->setSortOrder(0);
                }

                $productImageSetTransfer->addProductImage($productImageTransfer);
                $productImageSetTransfers[] = $productImageSetTransfer;
            }
        }

        return $productImageSetTransfers;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, array<string, array<string, string>>>
     */
    protected function getAbstractProductImageKeysMap(DataSetInterface $dataSet): array
    {
        if (!$this->abstractProductImageKeys) {
            $pattern = $this->buildProductImageKeyRegexPattern(static::ABSTRACT_PRODUCT_IMAGES_KEY_PATTERN);
            $this->abstractProductImageKeys = $this->buildDataSetKeysMappingForPattern($dataSet, $pattern);
        }

        return $this->abstractProductImageKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, array<string, array<string, string>>>
     */
    protected function getConcreteProductImageKeysMap(DataSetInterface $dataSet): array
    {
        if (!$this->concreteProductImageKeys) {
            $pattern = $this->buildProductImageKeyRegexPattern(static::CONCRETE_PRODUCT_IMAGES_KEY_PATTERN);
            $this->concreteProductImageKeys = $this->buildDataSetKeysMappingForPattern($dataSet, $pattern);
        }

        return $this->concreteProductImageKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $keyPattern
     *
     * @return array<string, array<string, array<string, string>>>
     */
    protected function buildDataSetKeysMappingForPattern(DataSetInterface $dataSet, string $keyPattern): array
    {
        $keysMapping = [];

        foreach ($dataSet as $key => $value) { // phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable
            if (!preg_match($keyPattern, $key, $matches)) {
                continue;
            }

            $fullKey = $matches[0];
            $locale = $matches[static::KEY_LOCALE];
            $imageSet = $matches[static::KEY_IMAGE_SET_NAME];
            $property = $matches[static::KEY_PROPERTY];

            $keysMapping[$locale][$imageSet][$property] = $fullKey;
        }

        return $keysMapping;
    }

    /**
     * @param string $keyPattern
     *
     * @return string
     */
    protected function buildProductImageKeyRegexPattern(string $keyPattern): string
    {
        return strtr(
            sprintf('/^%s$/', str_replace('.', '\.', $keyPattern)),
            [
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_LOCALE => '(?P<' . static::KEY_LOCALE . '>[a-zA-Z0-9_-]+)',
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_IMAGE_SET_NAME => '(?P<' . static::KEY_IMAGE_SET_NAME . '>[a-zA-Z0-9_-]+)',
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_PROPERTY => sprintf(
                    '(?P<' . static::KEY_PROPERTY . '>%s)',
                    implode('|', static::SUPPORTED_PRODUCT_IMAGE_PROPERTIES),
                ),
            ],
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $localeName
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return int|null
     */
    protected function getLocaleIdByName(DataSetInterface $dataSet, string $localeName): ?int
    {
        if (strtolower($localeName) === static::LOCALE_DEFAULT) {
            return null;
        }

        $localeId = $dataSet[AddLocalesStep::KEY_LOCALES][$localeName] ?? null;
        if ($localeId === null) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Locale "%s%" not supported!')
                    ->setParameters(['%s%' => $localeName]),
            );
        }

        return $localeId;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $key
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return string
     */
    protected function getDataSetPropertyOrFail(DataSetInterface $dataSet, string $key): string
    {
        $value = $dataSet[$key] ?? null;

        if ($value === null || $value === '') {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Required column data "%s%" is missing.')
                    ->setParameters(['%s%' => $key]),
            );
        }

        return $value;
    }
}
