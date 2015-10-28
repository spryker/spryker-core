<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Product\Persistence\SpyAbstractProductQuery;
use Orm\Zed\Product\Persistence\SpyLocalizedAbstractProductAttributesQuery;
use Orm\Zed\Product\Persistence\SpyLocalizedProductAttributesQuery;
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
    public function queryProductWithAttributesAndAbstractProduct($concreteSku, $idLocale);

    /**
     * @param $idAbstractProduct
     *
     * @return SpyTaxSetQuery
     */
    public function queryTaxSetForAbstractProduct($idAbstractProduct);

    /**
     * @param string $sku
     *
     * @return SpyProductQuery
     */
    public function queryConcreteProductBySku($sku);

    /**
     * @param string $sku
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAbstractProductBySku($sku);

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
     * @param int $idAbstractProduct
     * @param int $fkCurrentLocale
     *
     * @return SpyLocalizedAbstractProductAttributesQuery
     */
    public function queryAbstractProductAttributeCollection($idAbstractProduct, $fkCurrentLocale);

    /**
     * @param int $idConcreteProduct
     * @param int $fkCurrentLocale
     *
     * @return SpyLocalizedProductAttributesQuery
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
