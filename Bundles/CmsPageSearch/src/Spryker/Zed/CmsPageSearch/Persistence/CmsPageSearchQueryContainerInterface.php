<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CmsPageSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryCmsPageVersionByIds(array $cmsPageIds);

    /**
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearchQuery
     */
    public function queryCmsPageSearchEntities(array $cmsPageIds);

    /**
     * @api
     *
     * @param array $localeNames
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocalesWithLocaleNames(array $localeNames);
}
