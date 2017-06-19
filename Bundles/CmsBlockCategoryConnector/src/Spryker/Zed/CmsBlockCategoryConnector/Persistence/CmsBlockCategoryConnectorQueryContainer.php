<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;


/**
 * @method CmsBlockCategoryConnectorPersistenceFactory getFactory()
 */
class CmsBlockCategoryConnectorQueryContainer extends AbstractQueryContainer implements CmsBlockCategoryConnectorQueryContainerInterface
{

    /**
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnector()
    {
        return $this->getFactory()
            ->createCmsBlockCategoryConnectorQuery();
    }

    /**
     * @param $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
    {
        return $this->queryCmsBlockCategoryConnector()
            ->filterByFkCategory(null, Criteria::NOT_EQUAL)
            ->filterByFkCmsBlock($idCmsBlock);
    }

}