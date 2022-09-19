<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\QueryContainer;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;

class CmsBlockGuiToCmsBlockQueryContainerBridge implements CmsBlockGuiToCmsBlockQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct($cmsBlockQueryContainer)
    {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name): SpyCmsBlockQuery
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockByName($name);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate(): SpyCmsBlockQuery
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockWithTemplate();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates(): SpyCmsBlockTemplateQuery
    {
        return $this->cmsBlockQueryContainer->queryTemplates();
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock): SpyCmsBlockStoreQuery
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock);
    }
}
