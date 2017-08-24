<?php


namespace Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer;


use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;

interface ProductAttributeGuiToProductAttributeQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey();

    /**
     * @param string[] $keys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKeys($keys);

    /**
     * @return SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute();

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue();

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int|null $offset
     * @param int $limit
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValueWithTranslation(
        $idProductManagementAttribute,
        $idLocale,
        $searchText = '',
        $offset = null,
        $limit = 10
    );

}