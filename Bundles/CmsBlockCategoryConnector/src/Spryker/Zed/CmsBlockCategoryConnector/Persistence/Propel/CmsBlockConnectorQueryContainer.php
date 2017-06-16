<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;


/**
 * @method CmsBlockConnectorPersistenceFactory getFactory()
 */
class CmsBlockConnectorQueryContainer implements CmsBlockConnectorQueryContainerInterface
{

    /**
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockConnectorQuery
     */
    public function queryCmsBlockConnector()
    {
        return $this->getFactory()
            ->createCmsBlockConnetorQuery();
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockConnectorQuery
     */
    public function queryCmsBlockConnectorByIdCategory($idCategory)
    {
        return $this->queryCmsBlockConnector()
            ->filterByFkCategory($idCategory);
    }
}