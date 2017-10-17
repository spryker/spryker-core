<?php


namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;

trait RetrievesProductCategoryFilterEntity
{
    /**
     * @var ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilterQueryContainer;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\Propel\AbstractSpyProductCategoryFilterQuery $productCategoryFilterQueryContainer
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
    }

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }

}