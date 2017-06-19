<?php

namespace Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractQueryContainerBridge implements ProductAbstractQueryContainerInterface
{

    /**
     * @var ProductQueryContainerInterface
     */
    protected $productAbstractQueryContainer;

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productAbstractQueryContainer = $productQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return $this->productAbstractQueryContainer
            ->queryProductAbstract();
    }

}