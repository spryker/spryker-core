<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Dependency\QueryContainer;

interface CmsPageSearchToCmsQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function queryAllCmsVersions();

    /**
     * @api
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPages();
}
