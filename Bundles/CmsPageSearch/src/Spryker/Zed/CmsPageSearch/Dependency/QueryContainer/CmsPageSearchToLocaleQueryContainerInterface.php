<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Dependency\QueryContainer;

interface CmsPageSearchToLocaleQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales();
}
