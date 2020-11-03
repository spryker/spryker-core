<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Communication\Table;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\Acl\Persistence\Map\SpyAclGroupTableMap;
use Orm\Zed\Acl\Persistence\SpyAclGroupQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeService;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Table
 * @group GroupTableQueryTest
 * Add your own group annotations below this line
 */
class GroupTableQueryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Acl\AclCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFetchDataCollectsCorrectAclGroups(): void
    {
        // Arrange
        $aclGroupTransfer1 = $this->tester->haveGroup();
        $aclGroupTransfer2 = $this->tester->haveGroup();
        $groupTableMock = new GroupTableMock(
            SpyAclGroupQuery::create(),
            $this->getUtilDateTimeServiceMock()
        );

        // Act
        $result = $groupTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $resultAclGroupNames = array_column($result, SpyAclGroupTableMap::COL_NAME);
        $this->assertContains($aclGroupTransfer1->getName(), $resultAclGroupNames);
        $this->assertContains($aclGroupTransfer2->getName(), $resultAclGroupNames);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeServiceMock(): UtilDateTimeServiceInterface
    {
        $utilDateTimeServiceMock = $this->getMockBuilder(UtilDateTimeService::class)->getMock();
        $utilDateTimeServiceMock
            ->method('formatDateTime')
            ->willReturn((new DateTime())->format('Y-m-d H:i:s'));

        return $utilDateTimeServiceMock;
    }
}
