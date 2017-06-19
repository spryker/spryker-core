<?php


namespace Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer;


interface ProductAbstractQueryContainerInterface
{

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithName($idLocale);

}