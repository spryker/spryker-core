<?php

namespace Spryker\Zed\CmsBlock\Persistence;


use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockQueryContainer extends AbstractQueryContainer implements CmsBlockQueryContainerInterface
{

    /**
     * @param int $idCmsBlock
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockById($idCmsBlock)
    {
        return $this->getFactory()
            ->createCmsBlockQuery()
            ->filterByIdCmsBlock($idCmsBlock);
    }

}