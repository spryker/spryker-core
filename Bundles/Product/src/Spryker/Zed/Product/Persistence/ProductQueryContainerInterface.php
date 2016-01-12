<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeTypeQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;

interface ProductQueryContainerInterface
{

    /**
     * @param string $skus
     * @param LocaleTransfer $locale
     *
     * @return SpyProductQuery
     */
    public function getProductWithAttributeQuery($skus, LocaleTransfer $locale);

    /**
     * @param string $concreteSku
     * @param int $idLocale
     *
     * @return SpyProductQuery
     */
    public function queryProductWithAttributesAndProductAbstract($concreteSku, $idLocale);

    /**
     * @param $idProductAbstract
     *
     * @return SpyTaxSetQuery
     */
    public function queryTaxSetForProductAbstract($idProductAbstract);

    /**
     * @param string $sku
     *
     * @return SpyProductQuery
     */
    public function queryConcreteProductBySku($sku);

    /**
     * @param string $sku
     *
     * @return SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku);

    /**
     * @param string $attributeName
     *
     * @return SpyProductAttributesMetadataQuery
     */
    public function queryAttributeByName($attributeName);

    /**
     * @param string $attributeType
     *
     * @return SpyProductAttributeTypeQuery
     */
    public function queryAttributeTypeByName($attributeType);

    /**
     * @param int $idProductAbstract
     * @param int $fkCurrentLocale
     *
     * @return SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractAttributeCollection($idProductAbstract, $fkCurrentLocale);

    /**
     * @param int $idConcreteProduct
     * @param int $fkCurrentLocale
     *
     * @return SpyProductLocalizedAttributesQuery
     */
    public function queryConcreteProductAttributeCollection($idConcreteProduct, $fkCurrentLocale);

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return self
     */
    public function joinConcreteProducts(ModelCriteria $expandableQuery);

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return self
     */
    public function joinProductQueryWithLocalizedAttributes(ModelCriteria $expandableQuery, LocaleTransfer $locale);

}
