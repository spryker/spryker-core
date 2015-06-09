<?php

namespace SprykerFeature\Zed\ProductOptions\Business\Model;

use SprykerFeature\Zed\ProductOptions\Persistence\ProductOptionsQueryContainerInterface;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionType;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValue;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionTypeTranslation;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValueTranslation;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValuePrice;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionValue;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionTypeExclusion;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionValueConstraint;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyConfigurationPreset;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyConfigurationPresetValue;
use SprykerFeature\Zed\ProductOptions\Dependency\Facade\ProductOptionsToProductInterface;
use SprykerFeature\Zed\ProductOptions\Dependency\Facade\ProductOptionsToLocaleInterface;
use SprykerFeature\Zed\ProductOptions\Business\Exception\MissingOptionTypeException;
use SprykerFeature\Zed\ProductOptions\Business\Exception\MissingOptionValueException;
use SprykerFeature\Zed\ProductOptions\Business\Exception\MissingProductOptionTypeException;
use SprykerFeature\Zed\ProductOptions\Business\Exception\MissingProductOptionValueException;

class DataImportWriter implements DataImportWriterInterface
{

    /**
     * @var ProductOptionsQueryContainerInterface
     */
    protected $queryContainer;


    /**
     * @var ProductOptionsToProductInterface
     */
    protected $productFacade;

    /**
     * @var ProductOptionsToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param ProductOptionsQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductOptionsQueryContainerInterface $queryContainer,
        ProductOptionsToProductInterface $productFacade,
        ProductOptionsToLocaleInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $importKeyOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     */
    public function importOptionType($importKeyOptionType, array $localizedNames = [], $importKeyTaxSet = null)
    {
        $optionTypeEntity = $this->queryContainer
            ->queryOptionTypeByImportKey($importKeyOptionType)
            ->findOne();

        if (null === $optionTypeEntity) {
            $optionTypeEntity = (new SpyOptionType())
                ->setImportKey($importKeyOptionType);
        }

        $this->createOrUpdateOptionTypeTranslations($optionTypeEntity, $localizedNames);

        $optionTypeEntity->save();

        return $optionTypeEntity->getIdOptionType();
    }

    /**
     * @param SpyOptionType $optionTypeEntity
     * @param array $localizedNames
     */
    private function createOrUpdateOptionTypeTranslations(SpyOptionType $optionTypeEntity, array $localizedNames)
    {
        foreach ($localizedNames as $localeName => $localizedOptionTypeName) {

            if (false === $this->localeFacade->hasLocale($localeName)) {
                continue;
            }

            $localeTransfer = $this->localeFacade->getLocale($localeName);

            $translationEntity = $this->queryContainer
                ->queryOptionTypeTranslationByFks($optionTypeEntity->getIdOptionType(), $localeTransfer->getIdLocale())
                ->findOne();

            if (null === $translationEntity) {
                $translationEntity = (new SpyOptionTypeTranslation())->setFkLocale($localeTransfer->getIdLocale());
            }

            $translationEntity->setName($localizedOptionTypeName);

            $optionTypeEntity->addSpyOptionTypeTranslation($translationEntity);
        }
    }

    /**
     * @param string $importKeyOptionValue
     * @param string $importKeyOptionType
     * @param array $localizedNames
     * @param float $price
     *
     * @return int
     *
     * @throws MissingOptionTypeException
     */
    public function importOptionValue($importKeyOptionValue, $importKeyOptionType, array $localizedNames = [], $price = null)
    {
        $idOptionType = $this->queryContainer
            ->queryOptionTypeIdByImportKey($importKeyOptionType)
            ->findOne();

        if (null === $idOptionType) {
            throw new MissingOptionTypeException(
                sprintf(
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyOptionType
                )
            );
        }

        $optionValueEntity = $this->queryContainer
            ->queryOptionValueByImportKeyAndFkOptionType($importKeyOptionValue, $idOptionType)
            ->findOne();

        if (null === $optionValueEntity) {
            $optionValueEntity = (new SpyOptionValue())
                ->setImportKey($importKeyOptionValue)
                ->setFkOptionType($idOptionType);
        }

        if (null !== $price) {
            $normalizedPrice = (int) str_replace('.', '', number_format($price, 2));
            $priceEntity = (new SpyOptionValuePrice())
                ->setPrice($normalizedPrice);
            $optionValueEntity->setSpyOptionValuePrice($priceEntity);
        }

        $this->createOrUpdateOptionValueTranslations($optionValueEntity, $localizedNames);

        $optionValueEntity->save();

        return $optionValueEntity->getIdOptionValue();
    }

    /**
     * @param SpyOptionValue $optionValueEntity
     * @param array $localizedNames
     */
    private function createOrUpdateOptionValueTranslations(SpyOptionValue $optionValueEntity, array $localizedNames)
    {
        foreach ($localizedNames as $localeName => $localizedOptionValueName) {

            if (false === $this->localeFacade->hasLocale($localeName)) {
                continue;
            }

            $localeTransfer = $this->localeFacade->getLocale($localeName);

            $translationEntity = $this->queryContainer
                ->queryOptionValueTranslationByFks($optionValueEntity->getIdOptionValue(), $localeTransfer->getIdLocale())
                ->findOne();

            if (null === $translationEntity) {
                $translationEntity = (new SpyOptionValueTranslation())
                    ->setFkLocale($localeTransfer->getIdLocale());
            }

            $translationEntity->setName($localizedOptionValueName);

            $optionValueEntity->addSpyOptionValueTranslation($translationEntity);
        }
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingOptionTypeException
     */
    public function importProductOptionType($sku, $importKeyOptionType, $isOptional = false, $sequence  = null)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $idOptionType = $this->queryContainer
            ->queryOptionTypeIdByImportKey($importKeyOptionType)
            ->findOne();

        if (null === $idOptionType) {
            throw new MissingOptionTypeException(
                sprintf(
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyOptionType
                )
            );
        }

        $productOptionTypeEntity = $this->queryContainer
            ->queryProductOptionTypeByFKs($idProduct, $idOptionType)
            ->findOne();

        if (null === $productOptionTypeEntity) {
            $productOptionTypeEntity = (new SpyProductOptionType)
                ->setFkProduct($idProduct)
                ->setFkOptionType($idOptionType);
        }

        $productOptionTypeEntity
            ->setIsOptional($isOptional)
            ->setSequence($sequence)
            ->save();

        return $productOptionTypeEntity->getIdProductOptionType();
    }

    /**
     * @param int $idProductOptionType
     * @param string $importKeyOptionValue
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionTypeException
     * @throws MissingOptionValueException
     */
    public function importProductOptionValue($idProductOptionType, $importKeyOptionValue, $sequence = null)
    {
        if ($this->queryContainer->queryProductOptonTypeById($idProductOptionType)->count() === 0) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve a product option type with import id %d, but it does not exist.',
                    $idProductOptionType
                )
            );
        }

        $optionValueId = $this->queryContainer
            ->queryOptionValueIdByImportKey($importKeyOptionValue)
            ->findOne();

        if (null === $optionValueId) {
            throw new MissingOptionValueException(
                sprintf(
                    'Tried to retrieve an option value with import key %s, but it does not exist.',
                    $importKeyOptionValue
                )
            );
        }

        $productOptionValue = $this->queryContainer
            ->queryProductOptionValueByFKs($idProductOptionType, $optionValueId)
            ->findOne();

        if (null === $productOptionValue) {
            $productOptionValue = (new SpyProductOptionValue)
                ->setFkOptionValue($optionValueId)
                ->setFkProductOptionType($idProductOptionType);
        }

        $productOptionValue
            ->setSequence($sequence)
            ->save();

        return $productOptionValue->getIdProductOptionValue();
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionTypeA
     * @param string $importKeyOptionTypeB
     *
     * @throws MissingOptionTypeException
     * @throw MissingProductOptionTypeException
     */
    public function importProductOptionTypeExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $idOptionTypeA = $this->queryContainer
            ->queryOptionTypeIdByImportKey($importKeyOptionTypeA)
            ->findOne();

        if (null === $idOptionTypeA) {
            throw new MissingOptionTypeException(
                sprintf(
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyOptionTypeA
                )
            );
        }

        $idOptionTypeB = $this->queryContainer
            ->queryOptionTypeIdByImportKey($importKeyOptionTypeB)
            ->findOne();

        if (null === $idOptionTypeB) {
            throw new MissingOptionTypeException(
                sprintf(
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyOptionTypeB
                )
            );
        }

        $idProductOptionTypeA = $this->queryContainer
            ->queryProductOptionTypeIdByFKs($idProduct, $idOptionTypeA)
            ->findOne();


        if (null === $idProductOptionTypeA) {
            throw new MissingProductOptionTypeException('Tried to retrieve a product option type, but it does not exist.');
        }

        $idProductOptionTypeB = $this->queryContainer
            ->queryProductOptionTypeIdByFKs($idProduct, $idOptionTypeB)
            ->findOne();

        if (null === $idProductOptionTypeB) {
            throw new MissingProductOptionTypeException('Tried to retrieve a product option type, but it does not exist.');
        }

        if ($this->queryContainer->queryProductOptionTypeExclusionByFks($idProductOptionTypeA, $idProductOptionTypeB)->count() > 0) {
            return;
        }

        $optionTypeExclusion = (new SpyProductOptionTypeExclusion)
            ->setFkProductOptionTypeA($idProductOptionTypeA)
            ->setFkProductOptionTypeB($idProductOptionTypeB);

        $optionTypeExclusion->save();
    }

    /**
     * @param string $sku
     * @param int $idProductOptionValueSource
     * @param string $importKeyOptionValueTarget
     * @param string $operator
     *
     * @throws MissingProductOptionValueException
     * @throws MissingOptionValueException
     * @throws MissingProductOptionValueException
     */
    public function importProductOptionValueConstraint($sku, $idProductOptionValueSource, $importKeyOptionValueTarget, $operator)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        if ($this->queryContainer->queryProductOptonValueById($idProductOptionValueSource)->count() === 0) {
            throw new MissingProductOptionValueException(
                sprintf(
                    'Tried to retrieve a product option value with id %d, but it does not exist.',
                    $idProductOptionValueSource
                )
            );
        }

        $optionValueBEntity = $this->queryContainer
            ->queryOptionValueByImportKey($importKeyOptionValueTarget)
            ->findOne();

        if (null === $optionValueBEntity) {
            throw new MissingOptionValueException(
                sprintf(
                    'Tried to retrieve an option value with import key %s, but it does not exist.',
                    $importKeyOptionValueTarget
                )
            );
        }

        $idProductOptionTypeB = $this->queryContainer
            ->queryProductOptionTypeIdByFKs($idProduct, $optionValueBEntity->getFkOptionType())
            ->findOne();

        if (null === $idProductOptionTypeB) {
            throw new MissingProductOptionTypeException('Tried to retrieve a product option type, but it does not exist.');
        }

        $idProductOptionValueB = $this->queryContainer
            ->queryProductOptionValueIdByFKs($idProductOptionTypeB, $optionValueBEntity->getIdOptionValue())
            ->findOne();

        if (null === $idProductOptionValueB) {
            throw new MissingProductOptionValueException('Tried to retrieve a product option value, but it does not exist.');
        }

        if ($this->queryContainer->queryProductOptionValueConstraintsByFks($idProductOptionValueSource, $idProductOptionValueB)->count() > 0) {
            return;
        }

        $optionValueConstraint = (new SpyProductOptionValueConstraint())
            ->setFkProductOptionValueA($idProductOptionValueSource)
            ->setFkProductOptionValueB($idProductOptionValueB)
            ->setOperator($operator);

        $optionValueConstraint->save();
     }

    /**
     * @param $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionValueException
     * @throws MissingOptionValueException
     * @throws MissingProductOptionValueException
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $presetConfig = (new SpyConfigurationPreset)
            ->setFkProduct($idProduct)
            ->setIsDefault($isDefault)
            ->setSequence($sequence);

        foreach ($importKeysOptionValues as $importKeyOptionValue) {

            $optionValueEntity = $this->queryContainer
                ->queryOptionValueByImportKey($importKeyOptionValue)
                ->findOne();

            if (null === $optionValueEntity) {
                throw new MissingOptionValueException(
                    sprintf(
                        'Tried to retrieve an option value with import key %s, but it does not exist.',
                        $importKeyOptionValue
                    )
                );
            }

            $idProductOptionType = $this->queryContainer
                ->queryProductOptionTypeIdByFKs($idProduct, $optionValueEntity->getFkOptionType())
                ->findOne();

            if (null === $idProductOptionType) {
                throw new MissingProductOptionTypeException('Tried to retrieve a product option type, but it does not exist.');
            }

            $idPoductOptionValue = $this->queryContainer
                ->queryProductOptionValueIdByFKs($idProductOptionType, $optionValueEntity->getIdOptionValue())
                ->findOne();

            if (null === $idPoductOptionValue) {
                throw new MissingProductOptionValueException('Tried to retrieve a product option value, but it does not exist.');
            }

            $configPresetValue = (new SpyConfigurationPresetValue())
                ->setFkProductOptionValue($idPoductOptionValue);

            $presetConfig->addSpyConfigurationPresetValue($configPresetValue);
        }

        $presetConfig->save();

        return $presetConfig->getIdConfigurationPreset();
    }
}
