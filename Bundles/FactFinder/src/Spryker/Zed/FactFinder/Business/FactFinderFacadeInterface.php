<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Orm\Zed\Locale\Persistence\Base\SpyLocale;

interface FactFinderFacadeInterface
{

    /**
     * @api
     *
     * @param \Orm\Zed\Locale\Persistence\Base\SpyLocale $locale
     *
     * @return mixed
     */
    public function createFactFinderCsv(SpyLocale $locale);

    /**
     * @api
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocaleQuery();

    /**
     * @api
     *
     * @param int $idLocale
     * @param int $rootCategoryNodeId
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getParentCategoryQuery($idLocale, $rootCategoryNodeId);

}
