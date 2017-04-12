<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Persistence\Collector\Search\Propel;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CmsVersionPageCollectorQuery extends AbstractCmsVersionPageCollector
{

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        parent::prepareQuery();

        $this->touchQuery->addAnd(
            SpyCmsPageTableMap::COL_IS_SEARCHABLE,
            true,
            Criteria::EQUAL
        );
    }

}
