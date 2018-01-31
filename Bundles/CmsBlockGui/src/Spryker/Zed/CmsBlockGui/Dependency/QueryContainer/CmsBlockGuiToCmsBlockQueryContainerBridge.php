<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\QueryContainer;

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
    public function queryCmsBlockByName($name)
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockByName($name);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate()
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockWithTemplate();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates()
    {
        return $this->cmsBlockQueryContainer->queryTemplates();
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock)
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock);
    }
}
