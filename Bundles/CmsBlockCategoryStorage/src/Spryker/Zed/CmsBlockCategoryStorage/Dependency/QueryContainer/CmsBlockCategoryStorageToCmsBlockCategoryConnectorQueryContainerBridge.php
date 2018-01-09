<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Dependency\QueryContainer;

class CmsBlockCategoryStorageToCmsBlockCategoryConnectorQueryContainerBridge implements CmsBlockCategoryStorageToCmsBlockCategoryConnectorQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $cmsBlockCategoryConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainerFacade
     */
    public function __construct($cmsBlockCategoryConnectorQueryContainerFacade)
    {
        $this->cmsBlockCategoryConnectorQueryContainer = $cmsBlockCategoryConnectorQueryContainerFacade;
    }

    /**
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnector()
    {
        return $this->cmsBlockCategoryConnectorQueryContainer->queryCmsBlockCategoryConnector();
    }
}
