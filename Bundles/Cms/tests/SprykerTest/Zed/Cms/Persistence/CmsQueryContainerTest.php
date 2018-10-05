<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Spryker\Zed\Cms\Persistence\CmsPersistenceFactory;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Persistence
 * @group CmsQueryContainerTest
 * Add your own group annotations below this line
 */
class CmsQueryContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testQueryAllCmsVersionReturnCorrectQuery()
    {
        $cmsQueryContainer = new CmsQueryContainer();
        $cmsQueryContainer->setFactory(new CmsPersistenceFactory());
        $query = $cmsQueryContainer->queryAllCmsVersions();

        $this->assertInstanceOf(SpyCmsVersionQuery::class, $query);
    }
}
