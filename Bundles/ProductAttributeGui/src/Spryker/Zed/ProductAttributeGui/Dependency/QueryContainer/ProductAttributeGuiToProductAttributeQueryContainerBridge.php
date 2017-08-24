<?php


namespace Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer;


use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;

class ProductAttributeGuiToProductAttributeQueryContainerBridge implements ProductAttributeGuiToProductAttributeQueryContainerInterface
{

    /**
     * @var ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    public function __construct($productAttributeQueryContainer)
    {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->productAttributeQueryContainer
            ->queryProductAttributeKey();
    }

    /**
     * @param string[] $keys
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKeyByKeys($keys)
    {
        return $this->productAttributeQueryContainer
            ->queryProductAttributeKeyByKeys($keys);
    }

    /**
     * @return SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttribute()
    {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttribute();
    }

    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery
     */
    public function queryProductManagementAttributeValue()
    {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValue();
    }

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
    ) {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueWithTranslation(
                $idProductManagementAttribute,
                $idLocale,
                $searchText,
                $offset,
                $limit
            );
    }

}