<?php

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

        $this->createOrUpdateOptionTypeTranslations($productOptionTypeEntity, $localizedNames);

        $productOptionTypeEntity->save();

        return $productOptionTypeEntity->getIdProductOptionType();
    }

    /**
     * @param SpyProductOptionType $productOptionTypeEntity
     * @param array $localizedNames
     */
    private function createOrUpdateOptionTypeTranslations(SpyProductOptionType $productOptionTypeEntity, array $localizedNames)
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
                $translationEntity = (new SpyProductOptionTypeTranslation())->setFkLocale($localeTransfer->getIdLocale());
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
     * @return int
     *
     * @throws MissingProductOptionTypeException
     */
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null)
    {
        $idProductOptionType = $this->queryContainer
            ->queryProductOptionTypeIdByImportKey($importKeyProductOptionType)
            ->findOne();

        if (null === $idProductOptionType) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyProductOptionType
                )
            );
        }

        $optionValueEntity = $this->queryContainer
            ->queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $idProductOptionType)
            ->findOne();

        if (null === $optionValueEntity) {
            $optionValueEntity = (new SpyProductOptionValue())
                ->setImportKey($importKeyProductOptionValue)
                ->setFkProductOptionType($idProductOptionType);
        }

        if (null !== $price) {
            $normalizedPrice = (int) str_replace('.', '', number_format($price, 2));
            $priceEntity = (new SpyProductOptionValuePrice())
                ->setPrice($normalizedPrice);
            $optionValueEntity->setSpyProductOptionValuePrice($priceEntity);
        }

        $this->createOrUpdateOptionValueTranslations($optionValueEntity, $localizedNames);

        $optionValueEntity->save();

        return $optionValueEntity->getIdProductOptionValue();
    }

    /**
     * @param SpyProductOptionValue $optionValueEntity
     * @param array $localizedNames
     */
    private function createOrUpdateOptionValueTranslations(SpyProductOptionValue $optionValueEntity, array $localizedNames)
    {
        foreach ($localizedNames as $localeName => $localizedOptionValueName) {

            if (false === $this->localeFacade->hasLocale($localeName)) {
                continue;
            }

            $localeTransfer = $this->localeFacade->getLocale($localeName);

            $translationEntity = $this->queryContainer
                ->queryProductOptionValueTranslationByFks($optionValueEntity->getIdProductOptionValue(), $localeTransfer->getIdLocale())
                ->findOne();

            if (null === $translationEntity) {
                $translationEntity = (new SpyProductOptionValueTranslation())
                    ->setFkLocale($localeTransfer->getIdLocale());
            }

            $translationEntity->setName($localizedOptionValueName);

            $optionValueEntity->addSpyProductOptionValueTranslation($translationEntity);
        }
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionTypeException
     */
    public function importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional = false, $sequence  = null)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $idProductOptionType = $this->queryContainer
            ->queryProductOptionTypeIdByImportKey($importKeyProductOptionType)
            ->findOne();

        if (null === $idProductOptionType) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyProductOptionType
                )
            );
        }

        $productOptionTypeUsageEntity = $this->queryContainer
            ->queryProductOptionTypeUsageByFKs($idProduct, $idProductOptionType)
            ->findOne();

        if (null === $productOptionTypeUsageEntity) {
            $productOptionTypeUsageEntity = (new SpyProductOptionTypeUsage)
                ->setFkProduct($idProduct)
                ->setFkProductOptionType($idProductOptionType);
        }

        $productOptionTypeUsageEntity
            ->setIsOptional($isOptional)
            ->setSequence($sequence)
            ->save();

        return $productOptionTypeUsageEntity->getIdProductOptionTypeUsage();
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param string $importKeyProductOptionValue
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionTypeUsageException
     * @throws MissingProductOptionValueException
     */
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence = null)
    {
        if ($this->queryContainer->queryProductOptonTypeUsageById($idProductOptionTypeUsage)->count() === 0) {
            throw new MissingProductOptionTypeUsageException(
                sprintf(
                    'Tried to retrieve a product option type with import id %d, but it does not exist.',
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
                    'Tried to retrieve an option value with import key %s, but it does not exist.',
                    $importKeyProductOptionValue
                )
            );
        }

        $productOptionValueUsage = $this->queryContainer
            ->queryProductOptionValueUsageByFKs($idProductOptionTypeUsage, $optionValueId)
            ->findOne();

        if (null === $productOptionValueUsage) {
            $productOptionValueUsage = (new SpyProductOptionValueUsage)
                ->setFkProductOptionValue($optionValueId)
                ->setFkProductOptionTypeUsage($idProductOptionTypeUsage);
        }

        $productOptionValueUsage
            ->setSequence($sequence)
            ->save();

        return $productOptionValueUsage->getIdProductOptionValueUsage();
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @throws MissingProductOptionTypeException
     * @throw MissingProductOptionTypeUsageException
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
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
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
                    'Tried to retrieve an option type with import key %s, but it does not exist.',
                    $importKeyProductOptionTypeB
                )
            );
        }

        $idProductOptionTypeUsageA = $this->queryContainer
            ->queryProductOptionTypeUsageIdByFKs($idProduct, $idProductOptionTypeA)
            ->findOne();


        if (null === $idProductOptionTypeUsageA) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type, but it does not exist.');
        }

        $idProductOptionTypeUsageB = $this->queryContainer
            ->queryProductOptionTypeUsageIdByFKs($idProduct, $idProductOptionTypeB)
            ->findOne();

        if (null === $idProductOptionTypeUsageB) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type, but it does not exist.');
        }

        if ($this->queryContainer->queryProductOptionTypeUsageExclusionByFks($idProductOptionTypeUsageA, $idProductOptionTypeUsageB)->count() > 0) {
            return;
        }

        $optionTypeExclusion = (new SpyProductOptionTypeUsageExclusion)
            ->setFkProductOptionTypeUsageA($idProductOptionTypeUsageA)
            ->setFkProductOptionTypeUsageB($idProductOptionTypeUsageB);

        $optionTypeExclusion->save();
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
                    'Tried to retrieve a product option value with id %d, but it does not exist.',
                    $idProductOptionValueUsageSource
                )
            );
        }

        $optionValueBEntity = $this->queryContainer
            ->queryProductOptionValueByImportKey($importKeyProductOptionValueTarget)
            ->findOne();

        if (null === $optionValueBEntity) {
            throw new MissingProductOptionValueException(
                sprintf(
                    'Tried to retrieve an option value with import key %s, but it does not exist.',
                    $importKeyProductOptionValueTarget
                )
            );
        }

        $idProductOptionTypeUsageB = $this->queryContainer
            ->queryProductOptionTypeUsageIdByFKs($idProduct, $optionValueBEntity->getFkProductOptionType())
            ->findOne();

        if (null === $idProductOptionTypeUsageB) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type, but it does not exist.');
        }

        $idProductOptionValueUsageB = $this->queryContainer
            ->queryProductOptionValueUsageIdByFKs($idProductOptionTypeUsageB, $optionValueBEntity->getIdProductOptionValue())
            ->findOne();

        if (null === $idProductOptionValueUsageB) {
            throw new MissingProductOptionValueUsageException('Tried to retrieve a product option value, but it does not exist.');
        }

        if ($this->queryContainer->queryProductOptionValueUsageConstraintsByFks($idProductOptionValueUsageSource, $idProductOptionValueUsageB)->count() > 0) {
            return;
        }

        $optionValueConstraint = (new SpyProductOptionValueUsageConstraint())
            ->setFkProductOptionValueUsageA($idProductOptionValueUsageSource)
            ->setFkProductOptionValueUsageB($idProductOptionValueUsageB)
            ->setOperator($operator);

        $optionValueConstraint->save();
     }

    /**
     * @param $sku
     * @param array $importKeysProductOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingProductOptionValueException
     * @throws MissingProductOptionValueUsageException
     */
    public function importPresetConfiguration($sku, array $importKeysProductOptionValues, $isDefault = false, $sequence = null)
    {
        $idProduct = $this->productFacade->getConcreteProductIdBySku($sku);

        $presetConfig = (new SpyProductOptionConfigurationPreset)
            ->setFkProduct($idProduct)
            ->setIsDefault($isDefault)
            ->setSequence($sequence);

        foreach ($importKeysProductOptionValues as $importKeyProductOptionValue) {

            $optionValueEntity = $this->queryContainer
                ->queryProductOptionValueByImportKey($importKeyProductOptionValue)
                ->findOne();

            if (null === $optionValueEntity) {
                throw new MissingProductOptionValueException(
                    sprintf(
                        'Tried to retrieve an option value with import key %s, but it does not exist.',
                        $importKeyProductOptionValue
                    )
                );
            }

            $idProductOptionTypeUsage = $this->queryContainer
                ->queryProductOptionTypeUsageIdByFKs($idProduct, $optionValueEntity->getFkProductOptionType())
                ->findOne();

            if (null === $idProductOptionTypeUsage) {
                throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type, but it does not exist.');
            }

            $idPoductOptionValue = $this->queryContainer
                ->queryProductOptionValueUsageIdByFKs($idProductOptionTypeUsage, $optionValueEntity->getIdProductOptionValue())
                ->findOne();

            if (null === $idPoductOptionValue) {
                throw new MissingProductOptionValueUsageException('Tried to retrieve a product option value, but it does not exist.');
            }

            $configPresetValue = (new SpyProductOptionConfigurationPresetValue())
                ->setFkProductOptionValueUsage($idPoductOptionValue);

            $presetConfig->addSpyProductOptionConfigurationPresetValue($configPresetValue);
        }

        $presetConfig->save();

        return $presetConfig->getIdProductOptionConfigurationPreset();
    }
}
