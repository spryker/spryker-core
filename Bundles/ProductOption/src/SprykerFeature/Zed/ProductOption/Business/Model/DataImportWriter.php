<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business\Model;

use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionType;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageExclusion;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageConstraint;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPreset;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPresetValue;
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
            ->findOneOrCreate()
        ;

        if ($productOptionTypeEntity->isNew()) {
            $productOptionTypeEntity->save();
        }

        $this->createOrUpdateOptionTypeTranslations($productOptionTypeEntity, $localizedNames);

        $associatedAbstractProductIds = $this->queryContainer
            ->queryAssociatedAbstractProductIdsForProductOptionType($productOptionTypeEntity->getIdProductOptionType())
            ->find()
        ;

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
        if ($productOptionTypeEntity->isNew()) {
            $productOptionTypeEntity->save();
        }

        foreach ($localizedNames as $localeName => $localizedOptionTypeName) {
            if (false === $this->localeFacade->hasLocale($localeName)) {
                continue;
            }

            $localeTransfer = $this->localeFacade->getLocale($localeName);

            $translationEntity = $this->queryContainer
                ->queryProductOptionTypeTranslationByFks($productOptionTypeEntity->getIdProductOptionType(), $localeTransfer->getIdLocale())
                ->findOneOrCreate();

            $translationEntity->setName($localizedOptionTypeName);
            $translationEntity->setFkProductOptionType($productOptionTypeEntity->getIdProductOptionType());
            $translationEntity->setFkLocale($localeTransfer->getIdLocale());
            $translationEntity->save();

            $productOptionTypeEntity->addSpyProductOptionTypeTranslation($translationEntity);
        }

        $productOptionTypeEntity->save();
    }

    /**
     * @param string $importKeyProductOptionValue
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param int $price
     *
     * @throws MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null)
    {
        $idProductOptionType = $this->getIdProductOptionType($importKeyProductOptionType);

        $productOptionValueEntity = $this->queryContainer
            ->queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $idProductOptionType)
            ->findOneOrCreate();

        if ($price !== null) {
            $priceEntity = (new SpyProductOptionValuePrice())
                ->setPrice($price);
            $productOptionValueEntity->setSpyProductOptionValuePrice($priceEntity);
        }

        if ($productOptionValueEntity->isNew()) {
            $productOptionValueEntity->save();
        }

        $this->createOrUpdateOptionValueTranslations($productOptionValueEntity, $localizedNames);

        $productOptionValueEntity->save();

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
                ->findOneOrCreate();

            $translationEntity->setName($localizedOptionValueName);
            $translationEntity->setFkLocale($localeTransfer->getIdLocale());
            $translationEntity->setFkProductOptionValue($productOptionValueEntity->getIdProductOptionValue());
            $translationEntity->save();

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

        $idProductOptionType = $this->getIdProductOptionType($importKeyProductOptionType);

        $productOptionTypeUsageEntity = $this->queryContainer
            ->queryProductOptionTypeUsageByFKs($idProduct, $idProductOptionType)
            ->findOneOrCreate();

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
        $this->checkHasProductOptionTypeUsage($idProductOptionTypeUsage);

        $productOptionValue = $this->getProductOptionValue($importKeyProductOptionValue);

        $productOptionValueUsageEntity = $this->queryContainer
            ->queryProductOptionValueUsageByFKs($idProductOptionTypeUsage, $productOptionValue->getIdProductOptionValue())
            ->findOneOrCreate()
        ;

        $productOptionValueUsageEntity
            ->setSequence($sequence)
            ->save()
        ;

        $idAbstractProduct = $this->queryContainer
            ->queryAbstractProductIdForProductOptionTypeUsage($idProductOptionTypeUsage)
            ->findOne()
            ->getIdAbstractProduct()
        ;

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

        $idProductOptionTypeA = $this->getIdProductOptionType($importKeyProductOptionTypeA);
        $idProductOptionTypeB = $this->getIdProductOptionType($importKeyProductOptionTypeB);

        $idProductOptionTypeUsageA = $this->getIdProductOptionTypeUsage($idProduct, $idProductOptionTypeA);
        $idProductOptionTypeUsageB = $this->getIdProductOptionTypeUsage($idProduct, $idProductOptionTypeB);

        if ($this->hasProductOptionTypeUsageExclusion($idProductOptionTypeUsageA, $idProductOptionTypeUsageB)) {
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
        $this->checkHasProductOptionValue($idProductOptionValueUsageSource);

        $productOptionValueB = $this->getProductOptionValue($importKeyProductOptionValueTarget);

        $idProductOptionTypeUsageB = $this->getIdProductOptionTypeUsage($idProduct, $productOptionValueB->getFkProductOptionType());
        $idProductOptionValueUsageB = $this->getIdProductOptionValueUsage($idProductOptionTypeUsageB, $productOptionValueB->getIdProductOptionValue());

        if ($this->hasProductOptionValueUsageConstraints($idProductOptionValueUsageSource, $idProductOptionValueUsageB)) {
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
            $productOptionValue = $this->getProductOptionValue($importKeyProductOptionValue);

            $idProductOptionTypeUsage = $this->getIdProductOptionTypeUsage($idProduct, $productOptionValue->getFkProductOptionType());
            $idProductOptionValueUsage = $this->getIdProductOptionValueUsage($idProductOptionTypeUsage, $productOptionValue->getIdProductOptionValue());

            $configPresetValueEntity = (new SpyProductOptionConfigurationPresetValue())
                ->setFkProductOptionValueUsage($idProductOptionValueUsage);

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

    /**
     * @param int $idAbstractProduct
     */
    protected function touchAbstractProductById($idAbstractProduct)
    {
        $this->productFacade->touchProductActive($idAbstractProduct);
    }

    /**
     * @param string $concreteSku
     */
    protected function touchAbstractProductByConcreteSku($concreteSku)
    {
        $idAbstractProduct = $this->productFacade->getAbstractProductIdByConcreteSku($concreteSku);
        $this->productFacade->touchProductActive($idAbstractProduct);
    }

    /**
     * @param string $importKeyProductOptionType
     *
     * @throws MissingProductOptionTypeException
     *
     * @return int
     */
    protected function getIdProductOptionType($importKeyProductOptionType)
    {
        $productOptionType = $this->queryContainer
            ->queryProductOptionTypeByImportKey($importKeyProductOptionType)
            ->findOne()
        ;

        if ($productOptionType === null) {
            throw new MissingProductOptionTypeException(
                sprintf(
                    'Tried to retrieve a product option type with import key %s, but it does not exist.',
                    $importKeyProductOptionType
                )
            );
        }

        return $productOptionType->getIdProductOptionType();
    }

    /**
     * @param string $importKeyProductOptionValue
     *
     * @throws MissingProductOptionValueException
     *
     * @return SpyProductOptionValue
     */
    protected function getProductOptionValue($importKeyProductOptionValue)
    {
        $productOptionValue = $this->queryContainer
            ->queryProductOptionValueByImportKey($importKeyProductOptionValue)
            ->findOne()
        ;

        if ($productOptionValue === null) {
            throw new MissingProductOptionValueException(
                sprintf(
                    'Tried to retrieve a product option value with import key %s, but it does not exist.',
                    $importKeyProductOptionValue
                )
            );
        }

        return $productOptionValue;
    }

    /**
     * @param $idProductOptionTypeUsage
     *
     * @return bool
     */
    protected function hasProductOptionTypeUsage($idProductOptionTypeUsage)
    {
        return $this->queryContainer->queryProductOptionTypeUsageById($idProductOptionTypeUsage)->count() === 0;
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @throws MissingProductOptionTypeUsageException
     */
    protected function checkHasProductOptionTypeUsage($idProductOptionTypeUsage)
    {
        if ($this->hasProductOptionTypeUsage($idProductOptionTypeUsage)) {
            throw new MissingProductOptionTypeUsageException(
                sprintf(
                    'Tried to retrieve a product option type usage with id %d, but it does not exist.',
                    $idProductOptionTypeUsage
                )
            );
        }
    }

    /**
     * @param int $idProductOptionValueUsageSource
     *
     * @return bool
     */
    protected function hasProductOptionValueUsage($idProductOptionValueUsageSource)
    {
        return $this->queryContainer->queryProductOptionValueUsageById($idProductOptionValueUsageSource)->count() > 0;
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @throws MissingProductOptionValueUsageException
     */
    protected function checkHasProductOptionValue($idProductOptionValueUsage)
    {
        if (!$this->hasProductOptionValueUsage($idProductOptionValueUsage)) {
            throw new MissingProductOptionValueUsageException(
                sprintf(
                    'Tried to retrieve a product option value usage with id %d, but it does not exist.',
                    $idProductOptionValueUsage
                )
            );
        }
    }

    /**
     * @param int $idProduct
     * @param int $idProductOptionType
     *
     * @throws MissingProductOptionTypeUsageException
     *
     * @return int
     */
    protected function getIdProductOptionTypeUsage($idProduct, $idProductOptionType)
    {
        $productOptionTypeUsage = $this->queryContainer
            ->queryProductOptionTypeUsageByFKs($idProduct, $idProductOptionType)
            ->findOne()
        ;

        if ($productOptionTypeUsage === null) {
            throw new MissingProductOptionTypeUsageException('Tried to retrieve a product option type usage, but it does not exist.');
        }

        return $productOptionTypeUsage->getIdProductOptionTypeUsage();
    }

    /**
     * @param int $idProductOptionTypeUsageA
     * @param int $idProductOptionTypeUsageB
     *
     * @return bool
     */
    protected function hasProductOptionTypeUsageExclusion($idProductOptionTypeUsageA, $idProductOptionTypeUsageB)
    {
        return $this->queryContainer->queryProductOptionTypeUsageExclusionByFks($idProductOptionTypeUsageA, $idProductOptionTypeUsageB)->count() > 0;
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param int $idProductOptionValue
     *
     * @throws MissingProductOptionValueUsageException
     *
     * @return int
     */
    protected function getIdProductOptionValueUsage($idProductOptionTypeUsage, $idProductOptionValue)
    {
        $productOptionValueUsage = $this->queryContainer
            ->queryProductOptionValueUsageByFKs($idProductOptionTypeUsage, $idProductOptionValue)
            ->findOne();

        if ($productOptionValueUsage === null) {
            throw new MissingProductOptionValueUsageException('Tried to retrieve a product option value usage, but it does not exist.');
        }

        return $productOptionValueUsage->getIdProductOptionValueUsage();
    }

    /**
     * @param int $idProductOptionValueUsageSource
     * @param int $idProductOptionValueUsage
     *
     * @return bool
     */
    protected function hasProductOptionValueUsageConstraints($idProductOptionValueUsageSource, $idProductOptionValueUsage)
    {
        return $this->queryContainer->queryProductOptionValueUsageConstraintsByFks($idProductOptionValueUsageSource, $idProductOptionValueUsage)->count() > 0;
    }

}
