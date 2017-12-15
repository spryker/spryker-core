<?php

namespace Spryker\Zed\CategoryNavigationConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\Persistence\CategoryNavigationConnectorPersistenceFactory getFactory()
 */
class CategoryNavigationConnectorQueryContainer extends AbstractQueryContainer implements CategoryNavigationConnectorQueryContainerInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode)
    {
        return $this->getFactory()->getCategoryQueryContainer()->queryResourceUrlByCategoryNodeId($idCategoryNode);
    }
}
