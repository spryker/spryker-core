<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Dependency\QueryContainer;

class NavigationGuiToCmsBridge implements NavigationGuiToCmsInterface
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
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function queryCmsPageLocalizedAttributes()
    {
        return $this->cmsQueryContainer->queryCmsPageLocalizedAttributes();
    }

}
