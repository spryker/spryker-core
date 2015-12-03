<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $skus
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductWithAttributeQuery($skus, LocaleTransfer $locale);

    /**
     * @param string $concreteSku
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductWithAttributesAndProductAbstract($concreteSku, $idLocale);

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetForProductAbstract($idProductAbstract);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku);

    /**
     * @param string $attributeName
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadataQuery
     */
    public function queryAttributeByName($attributeName);

    /**
     * @param string $attributeType
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeTypeQuery
     */
    public function queryAttributeTypeByName($attributeType);

    /**
     * @param int $idProductAbstract
     * @param int $fkCurrentLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractAttributeCollection($idProductAbstract, $fkCurrentLocale);

    /**
     * @param int $idProductConcrete
     * @param int $fkCurrentLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductConcreteAttributeCollection($idProductConcrete, $fkCurrentLocale);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinProductConcreteCollection(ModelCriteria $expandableQuery);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return $this
     */
    public function joinProductQueryWithLocalizedAttributes(ModelCriteria $expandableQuery, LocaleTransfer $locale);

}
