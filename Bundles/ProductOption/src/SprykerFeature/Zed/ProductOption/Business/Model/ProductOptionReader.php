<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business\Model;

use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;

class ProductOptionReader implements ProductOptionReaderInterface
{

    const COL_PRICE = 'SpyProductOptionValuePrice.Price';

    const COL_TRANSLATION_TYPE = 'SpyProductOptionTypeTranslation.Name';

    const COL_TRANSLATION_VALUE = 'SpyProductOptionValueTranslation.Name';


    /**
     * @var ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param ProductOptionQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductOptionQueryContainerInterface $queryContainer
    ) {
        return $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idProductOptionValueUsage
     * @param int $idLocale
     *
     * @return ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $idLocale)
    {
        $productOptionTransfer = (new ProductOptionTransfer)
            ->setIdOptionValueUsage($idProductOptionValueUsage)
            ->setFkLocale($idLocale);

        $result =  $this->queryContainer->queryProductOptionValueUsageWithAssociatedAttributes(
            $idProductOptionValueUsage, $idLocale
        )->select([
            self::COL_PRICE,
            self::COL_TRANSLATION_TYPE,
            self::COL_TRANSLATION_VALUE
        ])->findOne();

        $productOptionTransfer->setLabelOptionType(
            $result[self::COL_TRANSLATION_TYPE]
        );

        $productOptionTransfer->setLabelOptionValue(
            $result[self::COL_TRANSLATION_VALUE]
        );

        $price = $result[self::COL_PRICE];
        if (null === $price) {
            $productOptionTransfer->setPrice(0);
        } else {
            $productOptionTransfer->setPrice((int) $price);
        }

        $taxSetEntity = $this->queryContainer->queryTaxSetForProductOptionValueUsage($idProductOptionValueUsage)
            ->findOne();
        if (null !== $taxSetEntity) {
            $this->addTaxesToProductOptionTransfer($productOptionTransfer, $taxSetEntity);
        }

        return $productOptionTransfer;
    }

    /**
     * @param ProductOptionTransfer $productOptionTransfer
     * @param SpyTaxSet $taxSetEntity
     */
    private function addTaxesToProductOptionTransfer(ProductOptionTransfer $productOptionTransfer, SpyTaxSet $taxSetEntity)
    {
        $taxTransfer = (new TaxSetTransfer)
            ->setIdTaxSet($taxSetEntity->getIdTaxSet())
            ->setName($taxSetEntity->getName());

        foreach ($taxSetEntity->getSpyTaxRates() as $taxRate) {

            $taxRateTransfer = (new TaxRateTransfer)
                ->setIdTaxRate($taxRate->getIdTaxRate())
                ->setName($taxRate->getName())
                ->setRate($taxRate->getRate());

            $taxTransfer->addTaxRate($taxRateTransfer);
        }

        $productOptionTransfer->setTaxSet($taxTransfer);
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForConcreteProduct($idProduct, $idLocale)
    {
        return $this->queryContainer->queryTypeUsagesForConcreteProduct($idProduct, $idLocale);
    }

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idTypeUsage, $idLocale)
    {
        return $this->queryContainer->queryValueUsagesForTypeUsage($idTypeUsage, $idLocale);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idTypeUsage)
    {
        return $this->queryContainer->queryTypeExclusionsForTypeUsage($idTypeUsage);
    }

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage)
    {
        return $this->queryContainer->queryValueConstraintsForValueUsage($idValueUsage);
    }

    /**
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function getValueConstraintsForValueUsageByOperator($idValueUsage, $operator)
    {
        return $this->queryContainer->queryValueConstraintsForValueUsageByOperator($idValueUsage, $operator);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForConcreteProduct($idProduct)
    {
        return $this->queryContainer->queryConfigPresetsForConcreteProduct($idProduct);
    }

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset)
    {
        return $this->queryContainer->queryValueUsagesForConfigPreset($idConfigPreset);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idTypeUsage)
    {
        return $this->queryContainer->queryEffectiveTaxRateForTypeUsage($idTypeUsage);
    }

}
