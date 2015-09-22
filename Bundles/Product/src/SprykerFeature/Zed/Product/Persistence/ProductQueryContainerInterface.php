<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedAbstractProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributesMetadataQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributeTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

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
