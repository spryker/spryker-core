<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Orm\Zed\Locale\Persistence\Base\SpyLocale;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderBusinessFactory getFactory()
 */
class FactFinderFacade extends AbstractFacade implements FactFinderFacadeInterface
{

    /**
     * @api
     *
     * @param \Orm\Zed\Locale\Persistence\Base\SpyLocale $locale
     *
     * @return mixed
     */
    public function createFactFinderCsv(SpyLocale $locale)
    {
        $this->getFactory()
            ->createCsvFile($locale);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocaleQuery()
    {
        return $this->getFactory()
            ->getLocaleQuery();
    }

    /**
     * @api
     *
     * @param int $idLocale
     * @param int $rootCategoryNodeId
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getParentCategoryQuery($idLocale, $rootCategoryNodeId)
    {
        return $this->getFactory()
            ->getFactFinderQueryContainer()
            ->getParentCategoryQuery($idLocale, $rootCategoryNodeId);
    }

}
