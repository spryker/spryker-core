<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business\Model;

use SprykerFeature\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
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
     * @var ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param ProductOptionQueryContainerInterface $queryContainer
     * @param ProductOptionToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $queryContainer,
        ProductOptionToLocaleInterface $localeFacade

    ) {
        $this->queryContainer = $queryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductOptionValueUsage
     * @param string $localeCode
     *
     * @return ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $localeCode)
    {
        $localeTransfer = $this->localeFacade->getLocale($localeCode);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdOptionValueUsage($idProductOptionValueUsage)
            ->setLocaleCode($localeCode);

        $result =  $this->queryContainer->queryProductOptionValueUsageWithAssociatedAttributes(
            $idProductOptionValueUsage, $localeTransfer->getIdLocale()
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
            $productOptionTransfer->setGrossPrice(0);
        } else {
            $productOptionTransfer->setGrossPrice((int) $price);
        }

        $taxSetEntity = $this->queryContainer->queryTaxSetForProductOptionValueUsage($idProductOptionValueUsage)
            ->findOne();
        if (null !== $taxSetEntity) {
            $this->addTaxesToProductOptionTransfer($productOptionTransfer, $taxSetEntity);
        }

        return $productOptionTransfer;
    }

    public function getProductOptionsByIdProduct($idProduct, $localeCode)
    {
/*
SELECT spotu.*, spot.import_key , spovu.fk_product_option_value, spovt.name, spovt.fk_locale
FROM spy_product_option_type_usage AS spotu
LEFT JOIN spy_product_option_type AS spot ON (spot.id_product_option_type = spotu.fk_product_option_type)
LEFT JOIN spy_product_option_value_usage AS spovu ON (spovu.fk_product_option_type_usage = spotu.fk_product_option_type)
LEFT JOIN spy_product_option_value_translation AS spovt ON (spovt.fk_product_option_value = spovu.fk_product_option_value AND spovt.fk_locale = 46)
WHERE spotu.fk_product = 2
#GROUP BY spotu.id_product_option_type_usage
GROUP BY spotu.fk_product_option_type
*/

        $localeTransfer = $this->localeFacade->getLocale($localeCode);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdOptionValueUsage($idProduct)
            ->setLocaleCode($localeCode);

        $result =  $this->queryContainer->queryProductOptionValueUsageWithAssociatedAttributes(
            $idProduct, $localeTransfer->getIdLocale()
        )->select([
            self::COL_PRICE,
            self::COL_TRANSLATION_TYPE,
            self::COL_TRANSLATION_VALUE
        ])->find();

        dump($result);
        die;

        $productOptionTransfer->setLabelOptionType(
            $result[self::COL_TRANSLATION_TYPE]
        );

        $productOptionTransfer->setLabelOptionValue(
            $result[self::COL_TRANSLATION_VALUE]
        );

        $price = $result[self::COL_PRICE];
        if (null === $price) {
            $productOptionTransfer->setGrossPrice(0);
        } else {
            $productOptionTransfer->setGrossPrice((int) $price);
        }

        $taxSetEntity = $this->queryContainer->queryTaxSetForProductOptionValueUsage($idProduct)
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
        $taxTransfer = new TaxSetTransfer();
        $taxTransfer->setIdTaxSet($taxSetEntity->getIdTaxSet())
            ->setName($taxSetEntity->getName());

        foreach ($taxSetEntity->getSpyTaxRates() as $taxRate) {

            $taxRateTransfer = new TaxRateTransfer();
            $taxRateTransfer->setIdTaxRate($taxRate->getIdTaxRate())
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
