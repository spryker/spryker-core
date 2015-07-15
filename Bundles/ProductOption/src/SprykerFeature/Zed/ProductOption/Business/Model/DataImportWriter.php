<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business\Model;

use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValue;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeTranslation;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueTranslation;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValuePrice;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageExclusion;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageConstraint;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionConfigurationPreset;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionConfigurationPresetValue;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionValueException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException;

class DataImportWriter implements DataImportWriterInterface
{

    /**
     * @var ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var ProductOptionToProductInterface
     */
    protected $productFacade;

    /**
     * @var ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param ProductOptionQueryContainerInterface $queryContainer
     * @param ProductOptionToProductInterface $productFacade
     * @param ProductOptionToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $queryContainer,
        ProductOptionToProductInterface $productFacade,
        ProductOptionToLocaleInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyProductOptionType, array $localizedNames = [], $importKeyTaxSet = null)
    {
        $productOptionTypeEntity = $this->queryContainer
            ->queryProductOptionTypeByImportKey($importKeyProductOptionType)
            ->findOne();

        if (null === $productOptionTypeEntity) {
            $productOptionTypeEntity = (new SpyProductOptionType())
                ->setImportKey($importKeyProductOptionType);
        }

        $productOptionTypeEntity->save();

        $this->createOrUpdateOptionTypeTranslations($productOptionTypeEntity, $localizedNames);

        $associatedAbstractProductIds = $this->queryContainer
            ->queryAssociatedAbstractProductIdsForProductOptionType($productOptionTypeEntity->getIdProductOptionType())
            ->find();

        foreach ($associatedAbstractProductIds as $idAbstractProduct) {
            $this->touchAbstractProductById($idAbstractProduct);
        }

        return $productOptionTypeEntity->getIdProductOptionType();
    }

    /**
     * @param SpyProductOptionType $productOptionTypeEntity
     * @param array $localizedNames
     */
    protected function createOrUpdateOptionTypeTranslations(SpyProductOptionType $productOptionTypeEntity, array $localizedNames)
    {
        foreach ($localizedNames as $localeName => $localizedOptionTypeName) {

            if (false === $this->localeFacade->hasLocale($localeName)) {
                continue;
            }

            $localeTransfer = $this->localeFacade->getLocale($localeName);

            $translationEntity = $this->queryContainer
                ->queryProductOptionTypeTranslationByFks($productOptionTypeEntity->getIdProductOptionType(), $localeTransfer->getIdLocale())
                ->findOne();

            if (null === $translationEntity) {
                $translationEntity = (new SpyProductOptionTypeTranslation())
                    ->setFkLocale($localeTransfer->getIdLocale());
            }

            $translationEntity->setName($localizedOptionTypeName);

            $productOptionTypeEntity->addSpyProductOptionTypeTranslation($translationEntity);
        }
    }

    /**
     * @param string $importKeyProductOptionValue
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param float $price
     *
     * @throws MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null)
    {
        $idProductOptionType = $this->queryContainer
            ->queryProductOptionTypeIdByImportKey($importKeyProductOptionType)
            ->findOne();

        if (null === $idProductOptionType) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve a product option type with import key %s, but it does not exist.',
                    $importKeyProductOptionType
                )
            );
        }

        $productOptionValueEntity = $this->queryContainer
            ->queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $idProductOptionType)
            ->findOne();

        if (null === $productOptionValueEntity) {
            $productOptionValueEntity = (new SpyProductOptionValue())
                ->setImportKey($importKeyProductOptionValue)
                ->setFkProductOptionType($idProductOptionType);
        }

        if (null !== $price) {
            $normalizedPrice = (int) str_replace('.', '', number_format($price, 2));
            $priceEntity = (new SpyProductOptionValuePrice())
                ->setPrice($normalizedPrice);
            $productOptionValueEntity->setSpyProductOptionValuePrice($priceEntity);
        }

        $productOptionValueEntity->save();

        $this->createOrUpdateOptionValueTranslations($productOptionValueEntity, $localizedNames);

        $associatedAbstractProductIds = $this->queryContainer
            ->queryAssociatedAbstractProductIdsForProductOptionValue($productOptionValueEntity->getIdProductOptionValue())
            ->find();

        foreach ($associatedAbstractProductIds as $idAbstractProduct) {
            $this->touchAbstractProductById($idAbstractProduct);
        }

        return $productOptionValueEntity->getIdProductOptionValue();
    }

    /**
     * @param SpyProductOptionValue $productOptionValueEntity
     * @param array $localizedNames
     */
    protected function createOrUpdateOptionValueTranslations(SpyProductOptionValue $productOptionValueEntity, array $localizedNames)
    {
        foreach ($localizedNames as $localeName => $localizedOptionValueName) {

            if (false === $this->localeFacade->hasLocale($localeName)) {
                continue;
            }

            $localeTransfer = $this->localeFacade->getLocale($localeName);

            $translationEntity = $this->queryContainer
                ->queryProductOptionValueTranslationByFks($productOptionValueEntity->getIdProductOptionValue(), $localeTransfer->getIdLocale())
                ->findOne();

            if (null === $translationEntity) {
                $translationEntity = (new SpyProductOptionValueTranslation())
                    ->setFkLocale($localeTransfer->getIdLocale());
            }

            $translationEntity->setName($localizedOptionValueName);

            $productOptionValueEntity->addSpyProductOptionValueTranslation($translationEntity);
        }
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @throws MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional = false, $sequence = null)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $idProductOptionType = $this->queryContainer
            ->queryProductOptionTypeIdByImportKey($importKeyProductOptionType)
            ->findOne();

        if (null === $idProductOptionType) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve a product option type with import key %s, but it does not exist.',
                    $importKeyProductOptionType
                )
            );
        }

        $productOptionTypeUsageEntity = $this->queryContainer
            ->queryProductOptionTypeUsageByFKs($idProduct, $idProductOptionType)
            ->findOne();

        if (null === $productOptionTypeUsageEntity) {
            $productOptionTypeUsageEntity = (new SpyProductOptionTypeUsage())
                ->setFkProduct($idProduct)
                ->setFkProductOptionType($idProductOptionType);
        }

        $productOptionTypeUsageEntity
            ->setIsOptional($isOptional)
            ->setSequence($sequence)
            ->save();

        $this->touchAbstractProductByConcreteSku($sku);

        return $productOptionTypeUsageEntity->getIdProductOptionTypeUsage();
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param string $importKeyProductOptionValue
     * @param int $sequence
     *
     * @throws MissingProductOptionTypeUsageException
     * @throws MissingProductOptionValueException
     *
     * @return int
     */
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence = null)
    {
        if ($this->queryContainer->queryProductOptonTypeUsageById($idProductOptionTypeUsage)->count() === 0) {
            throw new MissingProductOptionTypeUsageException(
                sprintf(
                    'Tried to retrieve a product option type usage with id %d, but it does not exist.',
                    $idProductOptionTypeUsage
                )
            );
        }

        $optionValueId = $this->queryContainer
            ->queryProductOptionValueIdByImportKey($importKeyProductOptionValue)
            ->findOne();

        if (null === $optionValueId) {
            throw new MissingProductOptionValueException(
                sprintf(
                    'Tried to retrieve a product option value with import key %s, but it does not exist.',
                    $importKeyProductOptionValue
                )
            );
        }

        $productOptionValueUsageEntity = $this->queryContainer
            ->queryProductOptionValueUsageByFKs($idProductOptionTypeUsage, $optionValueId)
            ->findOne();

        if (null === $productOptionValueUsageEntity) {
            $productOptionValueUsageEntity = (new SpyProductOptionValueUsage())
                ->setFkProductOptionValue($optionValueId)
                ->setFkProductOptionTypeUsage($idProductOptionTypeUsage);
        }

        $productOptionValueUsageEntity
            ->setSequence($sequence)
            ->save();

        $idAbstractProduct = $this->queryContainer
            ->queryAbstractProductIdForProductOptionTypeUsage($idProductOptionTypeUsage)
            ->findOne();

        $this->touchAbstractProductById($idAbstractProduct);

        return $productOptionValueUsageEntity->getIdProductOptionValueUsage();
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @throws MissingProductOptionTypeException
     * @throws MissingProductOptionTypeUsageException
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $idProductOptionTypeA = $this->queryContainer
            ->queryProductOptionTypeIdByImportKey($importKeyProductOptionTypeA)
            ->findOne();

        if (null === $idProductOptionTypeA) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve a product option type with import key %s, but it does not exist.',
                    $importKeyProductOptionTypeA
                )
            );
        }

        $idProductOptionTypeB = $this->queryContainer
            ->queryProductOptionTypeIdByImportKey($importKeyProductOptionTypeB)
            ->findOne();

        if (null === $idProductOptionTypeB) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve a product option type with import key %s, but it does not exist.',
                    $importKeyProductOptionTypeB
                )
            );
        }

        $idProductOptionTypeUsageA = $this->queryContainer
            ->queryProductOptionTypeUsageIdByFKs($idProduct, $idProductOptionTypeA)
            ->findOne();

        if (null === $idProductOptionTypeUsageA) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type usage, but it does not exist.');
        }

        $idProductOptionTypeUsageB = $this->queryContainer
            ->queryProductOptionTypeUsageIdByFKs($idProduct, $idProductOptionTypeB)
            ->findOne();

        if (null === $idProductOptionTypeUsageB) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type usage, but it does not exist.');
        }

        if ($this->queryContainer->queryProductOptionTypeUsageExclusionByFks($idProductOptionTypeUsageA, $idProductOptionTypeUsageB)->count() > 0) {
            return;
        }

        $optionTypeExclusionEntity = (new SpyProductOptionTypeUsageExclusion())
            ->setFkProductOptionTypeUsageA($idProductOptionTypeUsageA)
            ->setFkProductOptionTypeUsageB($idProductOptionTypeUsageB);

        $optionTypeExclusionEntity->save();

        $this->touchAbstractProductByConcreteSku($sku);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionValueUsageSource
     * @param string $importKeyProductOptionValueTarget
     * @param string $operator
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingProductOptionValueException
     * @throws MissingProductOptionValueUsageException
     */
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        if ($this->queryContainer->queryProductOptonValueUsageById($idProductOptionValueUsageSource)->count() === 0) {
            throw new MissingProductOptionValueUsageException(
                sprintf(
                    'Tried to retrieve a product option value usage with id %d, but it does not exist.',
                    $idProductOptionValueUsageSource
                )
            );
        }

        $productOptionValueBEntity = $this->queryContainer
            ->queryProductOptionValueByImportKey($importKeyProductOptionValueTarget)
            ->findOne();

        if (null === $productOptionValueBEntity) {
            throw new MissingProductOptionValueException(
                sprintf(
                    'Tried to retrieve a product option value with import key %s, but it does not exist.',
                    $importKeyProductOptionValueTarget
                )
            );
        }

        $idProductOptionTypeUsageB = $this->queryContainer
            ->queryProductOptionTypeUsageIdByFKs($idProduct, $productOptionValueBEntity->getFkProductOptionType())
            ->findOne();

        if (null === $idProductOptionTypeUsageB) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type usage, but it does not exist.');
        }

        $idProductOptionValueUsageB = $this->queryContainer
            ->queryProductOptionValueUsageIdByFKs($idProductOptionTypeUsageB, $productOptionValueBEntity->getIdProductOptionValue())
            ->findOne();

        if (null === $idProductOptionValueUsageB) {
            throw new MissingProductOptionValueUsageException('Tried to retrieve a product option value usage, but it does not exist.');
        }

        if ($this->queryContainer->queryProductOptionValueUsageConstraintsByFks($idProductOptionValueUsageSource, $idProductOptionValueUsageB)->count() > 0) {
            return;
        }

        $optionValueConstraintEntity = (new SpyProductOptionValueUsageConstraint())
            ->setFkProductOptionValueUsageA($idProductOptionValueUsageSource)
            ->setFkProductOptionValueUsageB($idProductOptionValueUsageB)
            ->setOperator($operator);

        $optionValueConstraintEntity->save();

        $this->touchAbstractProductByConcreteSku($sku);
     }

    /**
     * @param string $sku
     * @param array $importKeysProductOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingProductOptionValueException
     * @throws MissingProductOptionValueUsageException
     *
     * @return int
     */
    public function importPresetConfiguration($sku, array $importKeysProductOptionValues, $isDefault = false, $sequence = null)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $presetConfig = (new SpyProductOptionConfigurationPreset())
            ->setFkProduct($idProduct)
            ->setIsDefault($isDefault)
            ->setSequence($sequence);

        foreach ($importKeysProductOptionValues as $importKeyProductOptionValue) {

            $productOptionValueEntity = $this->queryContainer
                ->queryProductOptionValueByImportKey($importKeyProductOptionValue)
                ->findOne();

            if (null === $productOptionValueEntity) {
                throw new MissingProductOptionValueException(
                    sprintf(
                        'Tried to retrieve a product option value with import key %s, but it does not exist.',
                        $importKeyProductOptionValue
                    )
                );
            }

            $idProductOptionTypeUsage = $this->queryContainer
                ->queryProductOptionTypeUsageIdByFKs($idProduct, $productOptionValueEntity->getFkProductOptionType())
                ->findOne();

            if (null === $idProductOptionTypeUsage) {
                throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type usage, but it does not exist.');
            }

            $idPoductOptionValue = $this->queryContainer
                ->queryProductOptionValueUsageIdByFKs($idProductOptionTypeUsage, $productOptionValueEntity->getIdProductOptionValue())
                ->findOne();

            if (null === $idPoductOptionValue) {
                throw new MissingProductOptionValueUsageException('Tried to retrieve a product option value usage, but it does not exist.');
            }

            $configPresetValueEntity = (new SpyProductOptionConfigurationPresetValue())
                ->setFkProductOptionValueUsage($idPoductOptionValue);

            $presetConfig->addSpyProductOptionConfigurationPresetValue($configPresetValueEntity);
        }

        $presetConfig->save();

        $this->touchAbstractProductByConcreteSku($sku);

        return $presetConfig->getIdProductOptionConfigurationPreset();
    }

    public function flushBuffer()
    {
        // not implemented
    }

    protected function touchAbstractProductById($idAbstractProduct)
    {
        $this->productFacade->touchProductActive($idAbstractProduct);
    }

    protected function touchAbstractProductByConcreteSku($concreteSku)
    {
        $idAbstractProduct = $this->productFacade->getAbstractProductIdByConcreteSku($concreteSku);
        $this->productFacade->touchProductActive($idAbstractProduct);
    }

}
