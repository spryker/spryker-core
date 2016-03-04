<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\ProductOptionsNameValueTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionTypeTranslationTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTranslationTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionReader implements ProductOptionReaderInterface
{

    const COL_PRICE = 'SpyProductOptionValuePrice.Price';

    const COL_TRANSLATION_TYPE = 'SpyProductOptionTypeTranslation.Name';

    const COL_TRANSLATION_VALUE = 'SpyProductOptionValueTranslation.Name';

    const COL_TRANSLATION_TYPE_ALIAS = 'type';

    const COL_TRANSLATION_VALUE_ALIAS = 'value';

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface $localeFacade
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
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $localeCode)
    {
        $localeTransfer = $this->localeFacade->getLocale($localeCode);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdOptionValueUsage($idProductOptionValueUsage)
            ->setLocaleCode($localeCode);

        $result = $this->queryContainer->queryProductOptionValueUsageWithAssociatedAttributes(
            $idProductOptionValueUsage,
            $localeTransfer->getIdLocale()
        )->select([
            self::COL_PRICE,
            self::COL_TRANSLATION_TYPE,
            self::COL_TRANSLATION_VALUE,
        ])->findOne();

        $productOptionTransfer->setLabelOptionType(
            $result[self::COL_TRANSLATION_TYPE]
        );

        $productOptionTransfer->setLabelOptionValue(
            $result[self::COL_TRANSLATION_VALUE]
        );

        $price = $result[self::COL_PRICE];
        if ($price === null) {
            $productOptionTransfer->setUnitGrossPrice(0);
        } else {
            $productOptionTransfer->setUnitGrossPrice((int)$price);
        }

        $taxSetEntity = $this->queryContainer->queryTaxSetForProductOptionValueUsage($idProductOptionValueUsage)
            ->findOne();

        if ($taxSetEntity !== null) {
            $this->addTaxRate($productOptionTransfer, $taxSetEntity);
        }

        return $productOptionTransfer;
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return \Propel\Runtime\Collection\ArrayCollection
     */
    public function getProductOptionsByIdProductAndIdLocale($idProduct, $idLocale)
    {
        $query = $this->queryContainer
            ->queryProductOptionTypeUsageByIdProduct($idProduct)
            ->useSpyProductOptionTypeQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductOptionTypeTranslationQuery(null, Criteria::LEFT_JOIN)
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->useSpyProductOptionValueUsageQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductOptionValueQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductOptionValueTranslationQuery(null, Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyProductOptionTypeTranslationTableMap::COL_NAME, self::COL_TRANSLATION_TYPE_ALIAS)
            ->withColumn(SpyProductOptionValueTranslationTableMap::COL_NAME, self::COL_TRANSLATION_VALUE_ALIAS)
            ->clearGroupByColumns()
            ->groupByFkProductOptionType();

        $productOptionsCollection = $query->find();

        $productList = new ArrayCollection();
        foreach ($productOptionsCollection as $productOptionsItem) {
            $objectHash = spl_object_hash($productOptionsItem);

            // @todo CD-414 propel uses all columns in group by and it should not
            if (!$productList->offsetExists($objectHash)) {
                $option = $productOptionsItem->toArray();

                $productOptions = new ProductOptionsNameValueTransfer();
                $productOptions->setType($option[self::COL_TRANSLATION_TYPE_ALIAS]);
                $productOptions->setValue($option[self::COL_TRANSLATION_VALUE_ALIAS]);

                $productList[$objectHash] = $productOptions;
            }
        }

        return $productList;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return void
     */
    protected function addTaxRate(productOptionTransfer $productOptionTransfer, SpyTaxSet $taxSetEntity)
    {
        $taxRate = 0;
        foreach ($taxSetEntity->getSpyTaxRates() as $taxRateEntity) {
            $taxRate += $taxRateEntity->getRate();
        }
        $productOptionTransfer->setTaxRate($taxRate);
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForProductConcrete($idProduct, $idLocale)
    {
        return $this->queryContainer->queryTypeUsagesForProductConcrete($idProduct, $idLocale);
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale)
    {
        return $this->queryContainer->queryValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale);
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idProductOptionTypeUsage)
    {
        return $this->queryContainer->queryTypeExclusionsForTypeUsage($idProductOptionTypeUsage);
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
    public function getConfigPresetsForProductConcrete($idProduct)
    {
        return $this->queryContainer->queryConfigPresetsForProductConcrete($idProduct);
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
     * @param int $idProductAttributeTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage)
    {
        return $this->queryContainer->queryEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage);
    }

}
