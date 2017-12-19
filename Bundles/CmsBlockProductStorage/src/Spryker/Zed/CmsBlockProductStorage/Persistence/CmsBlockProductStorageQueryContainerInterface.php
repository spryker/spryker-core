<?php

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CmsBlockProductStorageQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param array $productIds
     *
     * @return SpyCmsBlockProductStorageQuery
     */
    public function queryCmsBlockProductStorageByIds(array $productIds);

    /**
     * @param array $productIds
     *
     * @return $this|\Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProducts(array $productIds);
}
