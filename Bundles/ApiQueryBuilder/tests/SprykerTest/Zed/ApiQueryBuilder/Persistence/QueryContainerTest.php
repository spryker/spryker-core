<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ApiQueryBuilder\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ApiQueryBuilder
 * @group Persistence
 * @group QueryContainerTest
 * Add your own group annotations below this line
 */
class QueryContainerTest extends Unit
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
    public function testBuildQueryFromRequest()
    {
        $apiFilter = new ApiFilterTransfer();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter($apiFilter);

        $columnSelectionTransfer = $this->getColumnSelectionTransfer();

        $apiQueryBuilderQueryTransfer = new ApiQueryBuilderQueryTransfer();
        $apiQueryBuilderQueryTransfer->setApiRequest($apiRequestTransfer);
        $apiQueryBuilderQueryTransfer->setColumnSelection($columnSelectionTransfer);

        $query = SpyProductQuery::create();

        $query = $this->apiQueryBuilderQueryContainer->buildQueryFromRequest($query, $apiQueryBuilderQueryTransfer);

        $this->assertInstanceOf(ModelCriteria::class, $query);
    }

    /**
     * @return void
     */
    public function testToPropelQueryBuilderCriteria()
    {
        $apiFilter = new ApiFilterTransfer();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiRequestTransfer->setFilter($apiFilter);

        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();

        $apiQueryBuilderQueryTransfer = new ApiQueryBuilderQueryTransfer();
        $apiQueryBuilderQueryTransfer->setApiRequest($apiRequestTransfer);
        $apiQueryBuilderQueryTransfer->setColumnSelection($columnSelectionTransfer);

        $criteriaTransfer = $this->apiQueryBuilderQueryContainer->toPropelQueryBuilderCriteria($apiQueryBuilderQueryTransfer);

        $this->assertInstanceOf(PropelQueryBuilderCriteriaTransfer::class, $criteriaTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function getColumnSelectionTransfer()
    {
        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();

        $tableAliases = SpyProductTableMap::getFieldNames(TableMap::TYPE_FIELDNAME);
        foreach ($tableAliases as $columnAlias) {
            $columnTransfer = new PropelQueryBuilderColumnTransfer();
            $columnTransfer->setName(SpyProductTableMap::TABLE_NAME . '.' . $columnAlias);
            $columnTransfer->setAlias($columnAlias);

            $columnSelectionTransfer->addTableColumn($columnTransfer);
        }

        return $columnSelectionTransfer;
    }
}
