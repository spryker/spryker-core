<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Dependency\QueryContainer;

use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;

class CmsUserConnectorToCmsQueryContainer implements CmsUserConnectorToCmsQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct($cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param int $idCmsVersion
     *
     * @return SpyCmsVersionQuery
     */
    public function queryCmsVersionById($idCmsVersion)
    {
        return $this->cmsQueryContainer->queryCmsVersionById($idCmsVersion);
    }
}
