<?php


namespace Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer;


interface ProductAbstractQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract();

}