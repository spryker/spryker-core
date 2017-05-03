<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ApiQueryBuilder\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ApiQueryBuilder
 * @group Persistence
 * @group QueryContainerTest
 */
class QueryContainerTest extends Test
{

    /**
     * @var \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainer
     */
    protected $apiQueryBuilderQueryContainer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->apiQueryBuilderQueryContainer = new ApiQueryBuilderQueryContainer();
    }

    /**
     * @return void
     */
    public function testToPropelQueryBuilderCriteria()
    {
        $apiFilter = new ApiFilterTransfer();
        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter($apiFilter);

        $criteriaTransfer = $this->apiQueryBuilderQueryContainer->toPropelQueryBuilderCriteria($apiRequestTransfer);

        $this->assertInstanceOf(PropelQueryBuilderCriteriaTransfer::class, $criteriaTransfer);
    }

}
