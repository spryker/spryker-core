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
    public function __construct($productQueryContainer)
    {
        $this->productAbstractQueryContainer = $productQueryContainer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithName($idLocale)
    {
        return $this->productAbstractQueryContainer
            ->queryProductAbstractWithName($idLocale);
    }

}