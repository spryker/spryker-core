<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Dependency\QueryContainer;

interface CmsStorageToLocaleQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales();
}
