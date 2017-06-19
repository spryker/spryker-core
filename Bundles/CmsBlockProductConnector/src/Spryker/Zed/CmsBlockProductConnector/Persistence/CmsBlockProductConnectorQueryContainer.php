<?php


namespace Spryker\Zed\CmsBlockProductConnector\Persistence;


use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method CmsBlockProductConnectorPersistenceFactory getFactory()
 */
class CmsBlockProductConnectorQueryContainer extends AbstractQueryContainer implements CmsBlockProductConnectorQueryContainerInterface
{
    /**
     * @return SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockCategoryConnector()
    {
        return $this->getFactory()
            ->createCmsBlockProductConnectorQuery();
    }

    /**
     * @param $idCmsBlock
     *
     * @return SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock)
    {
        return $this->queryCmsBlockCategoryConnector()
            ->filterByFkCmsBlock($idCmsBlock);
    }


}