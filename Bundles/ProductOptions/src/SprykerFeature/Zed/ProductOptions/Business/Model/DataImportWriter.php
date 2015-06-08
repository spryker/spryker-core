<?php

namespace SprykerFeature\Zed\ProductOptions\Business\Model;

use SprykerFeature\Zed\ProductOptions\Persistence\ProductOptionsQueryContainerInterface;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionType;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValue;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValuePrice;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionValue;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionTypeExclusion;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionValueConstraint;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyConfigurationPreset;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyConfigurationPresetValue;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Map\SpyOptionTypeTableMap;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Map\SpyOptionValueTableMap;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Map\SpyProductOptionTypeTableMap;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Map\SpyProductOptionValueTableMap;
use SprykerFeature\Zed\ProductOptions\Dependency\Facade\ProductOptionsToProductInterface;

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
     * @param ProductOptionsQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductOptionsQueryContainerInterface $queryContainer,
        ProductOptionsToProductInterface $productFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $importKeyOptionType
     * @param string $importKeyTaxSet
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importOptionType($importKeyOptionType, $importKeyTaxSet = null)
    {
        if (!$optionTypeEntity = $this->queryContainer->queryOptionTypeByImportKey($importKeyOptionType)->findOne()) {
            $optionTypeEntity = (new SpyOptionType())
                ->setImportKey($importKeyOptionType);
        }

        $optionTypeEntity->save();

        return $optionTypeEntity->getIdOptionType();
    }

    /**
     * @param string $importKeyOptionValue
     * @param string $importKeyOptionType
     * @param float $price
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importOptionValue($importKeyOptionValue, $importKeyOptionType, $price = null)
    {
        if (!$idOptionType = $this->queryContainer->queryOptionTypeByImportKey($importKeyOptionType)->select(SpyOptionTypeTableMap::COL_ID_OPTION_TYPE)->findOne()) {
            throw new \Exception("OptionType '$importKeyOptionType' not found");
        }

        $optionValueEntity =  $this->queryContainer->queryOptionValueByImportKeyAndFkOptionType($importKeyOptionValue, $idOptionType)->findOne();

        if (!$optionValueEntity) {
            $optionValueEntity = (new SpyOptionValue())
                ->setImportKey($importKeyOptionValue)
                ->setFkOptionType($idOptionType);
        }

        if ($price) {
            $normalizedPrice = (int) str_replace('.', '', number_format($price, 2));
            $priceEntity = (new SpyOptionValuePrice())->setPrice($normalizedPrice);
            $optionValueEntity->setSpyOptionValuePrice($priceEntity);
        }

        $optionValueEntity->save();

        return $optionValueEntity->getIdOptionValue();
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionType($sku, $importKeyOptionType, $isOptional = false, $sequence  = null)
    {
        if (!$idProduct = $this->productFacade->getConcreteProductIdBySku($sku)) {
            throw new \Exception("Product '$sku' not found");
        }

        if (!$idOptionType = $this->queryContainer->queryOptionTypeByImportKey($importKeyOptionType)->select(SpyOptionTypeTableMap::COL_ID_OPTION_TYPE)->findOne()) {
            throw new \Exception("OptionType $importKeyOptionType not found");
        }

        $productOptionTypeEntity = $this->queryContainer->queryProductOptionTypeByFKs($idProduct, $idOptionType)->findOne();

        if (!$productOptionTypeEntity) {
            $productOptionTypeEntity = (new SpyProductOptionType)
                ->setFkProduct($idProduct)
                ->setFkOptionType($idOptionType);
        }

        $productOptionTypeEntity->setIsOptional($isOptional)->setSequence($sequence);

        $productOptionTypeEntity->save();

        return $productOptionTypeEntity->getIdProductOptionType();
    }

    /**
     * @param int $idProductOptionType
     * @param string $importKeyOptionValue
     * @param int $sequence
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionValue($idProductOptionType, $importKeyOptionValue, $sequence = null)
    {
        if ($this->queryContainer->queryProductOptonTypeById($idProductOptionType)->count() === 0) {
            throw new \Exception("ProductOptionType '$idProductOptionType' for product '$idProductOptionType' not found");
        }

        if (!$optionValueId = $this->queryContainer->queryOptionValueByImportKey($importKeyOptionValue)->select(SpyOptionValueTableMap::COL_ID_OPTION_VALUE)->findOne()) {
            throw new \Exception("OptionValue '$importKeyOptionValue' not found");
        }

        $productOptionValue = $this->queryContainer->queryProductOptionValueByFKs($idProductOptionType, $optionValueId)->findOne();

        if (!$productOptionValue) {
            $productOptionValue = (new SpyProductOptionValue)
                ->setFkOptionValue($optionValueId)
                ->setFkProductOptionType($idProductOptionType);
        }

        $productOptionValue->setSequence($sequence);

        $productOptionValue->save();

        return $productOptionValue->getIdProductOptionValue();
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionTypeA
     * @param string $importKeyOptionTypeB
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionTypeExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB)
    {
        if (!$idProduct = $this->productFacade->getConcreteProductIdBySku($sku)) {
            throw new \Exception("Product '$sku' not found");
        }

        if (!$idOptionTypeA = $this->queryContainer->queryOptionTypeByImportKey($importKeyOptionTypeA)->select(SpyOptionTypeTableMap::COL_ID_OPTION_TYPE)->findOne()) {
            throw new \Exception("OptionType '$importKeyOptionTypeA' not found");
        }

        if (!$idOptionTypeB = $this->queryContainer->queryOptionTypeByImportKey($importKeyOptionTypeB)->select(SpyOptionTypeTableMap::COL_ID_OPTION_TYPE)->findOne()) {
            throw new \Exception("OptionType '$importKeyOptionTypeB' not found");
        }

        $idProductOptionTypeA = $this->queryContainer->queryProductOptionTypeByFKs($idProduct, $idOptionTypeA)->select(SpyProductOptionTypeTableMap::COL_ID_PRODUCT_OPTION_TYPE)->findOne();
        if (!$idProductOptionTypeA) {
            throw new \Exception("ProductOptionType '$importKeyOptionTypeA' for product '$sku' not found");
        }

        $idProductOptionTypeB = $this->queryContainer->queryProductOptionTypeByFKs($idProduct, $idOptionTypeB)->select(SpyProductOptionTypeTableMap::COL_ID_PRODUCT_OPTION_TYPE)->findOne();
        if (!$idProductOptionTypeB) {
            throw new \Exception("ProductOptionType '$importKeyOptionTypeB' for product '$sku' not found");
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
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionValueConstraint($sku, $idProductOptionValueSource, $importKeyOptionValueTarget, $operator)
    {
        if (!$idProduct = $this->productFacade->getConcreteProductIdBySku($sku)) {
            throw new \Exception("Product '$sku' not found");
        }

        if ($this->queryContainer->queryProductOptonValueById($idProductOptionValueSource)->count() === 0) {
            throw new \Exception("ProductOptionValue '$idProductOptionValueSource' not found");
        }

        if (!$optionValueBEntity = $this->queryContainer->queryOptionValueByImportKey($importKeyOptionValueTarget)->findOne()) {
            throw new \Exception("OptionValue $importKeyOptionValueTarget not found");
        }

        $idProductOptionTypeB = $this->queryContainer
            ->queryProductOptionTypeByFKs($idProduct, $optionValueBEntity->getFkOptionType())->select(SpyProductOptionTypeTableMap::COL_ID_PRODUCT_OPTION_TYPE)
            ->findOne();
        if (!$idProductOptionTypeB) {
            throw new \Exception("ProductOptionType for value '$importKeyOptionValueTarget' and product '$sku' not found");
        }

        $idProductOptionValueB = $this->queryContainer
            ->queryProductOptionValueByFKs($idProductOptionTypeB, $optionValueBEntity->getIdOptionValue())->select(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE)
            ->findOne();
        if (!$idProductOptionValueB) {
            throw new \Exception("ProductOptionValue '$importKeyOptionValueTarget' for product '$sku' not found");
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
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        if (!$idProduct = $this->productFacade->getConcreteProductIdBySku($sku)) {
            throw new \Exception("Product '$sku' not found");
        }

        $presetConfig = (new SpyConfigurationPreset)
            ->setFkProduct($idProduct)
            ->setIsDefault($isDefault)
            ->setSequence($sequence);

        foreach ($importKeysOptionValues as $importKeyOptionValue) {

            if (!$optionValueEntity = $this->queryContainer->queryOptionValueByImportKey($importKeyOptionValue)->findOne()) {
                throw new \Exception("OptionValue $importKeyOptionValue not found");
            }

            $idProductOptionType = $this->queryContainer
                ->queryProductOptionTypeByFKs($idProduct, $optionValueEntity->getFkOptionType())
                ->select(SpyProductOptionTypeTableMap::COL_ID_PRODUCT_OPTION_TYPE)
                ->findOne();
            if (!$idProductOptionType) {
                throw new \Exception("ProductOptionType for '$importKeyOptionValue' and product '$sku' not found");
            }

            $idPoductOptionValue = $this->queryContainer
                ->queryProductOptionValueByFKs($idProductOptionType, $optionValueEntity->getIdOptionValue())
                ->select(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE)
                ->findOne();
            if (!$idPoductOptionValue) {
                throw new \Exception("ProductOptionValue with '$importKeyOptionValue' for product '$sku' not found");
            }

            $configPresetValue = (new SpyConfigurationPresetValue())
                ->setFkProductOptionValue($idPoductOptionValue);

            $presetConfig->addSpyConfigurationPresetValue($configPresetValue);
        }

        $presetConfig->save();

        return $presetConfig->getIdConfigurationPreset();
    }
}
