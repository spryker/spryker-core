<?php

namespace SprykerFeature\Zed\Product\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedAbstractProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedProductAttributesQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributesMetadataQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductAttributeTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;

interface ProductQueryContainerInterface
{
    /**
     * @param string $skus
     * @param LocaleDto $locale
     *
     * @return SpyProductQuery
     */
    public function getProductWithAttributeQuery($skus, LocaleDto $locale);

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
     * @return ModelCriteria
     */
    public function joinLocalizedProductQueryWithAttributes(ModelCriteria $expandableQuery);
}
