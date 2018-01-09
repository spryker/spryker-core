<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Dependency\QueryContainer;

class CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerBridge implements CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface
     */
    protected $cmsBlockProductConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface $cmsBlockProductConnectorQueryContainerFacade
     */
    public function __construct($cmsBlockProductConnectorQueryContainerFacade)
    {
        $this->cmsBlockProductConnectorQueryContainer = $cmsBlockProductConnectorQueryContainerFacade;
    }

    /**
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnector()
    {
        return $this->cmsBlockProductConnectorQueryContainer->queryCmsBlockProductConnector();
    }
}
