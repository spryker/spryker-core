<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriter;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitWriter
 * @group CompanyBusinessUnitWriterTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitWriterTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriterInterface
     */
    protected $companyBusinessUnitWriter;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $repositoryMock;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityManagerMock;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $companyBusinessUnitPluginExecutorMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->repositoryMock = $this->getMockBuilder(CompanyBusinessUnitRepositoryInterface::class)->getMock();
        $this->entityManagerMock = $this->getMockBuilder(CompanyBusinessUnitEntityManagerInterface::class)->getMock();
        $this->companyBusinessUnitPluginExecutorMock = $this->getMockBuilder(CompanyBusinessUnitPluginExecutorInterface::class)->getMock();

        $this->companyBusinessUnitWriter = new CompanyBusinessUnitWriter(
            $this->repositoryMock,
            $this->entityManagerMock,
            $this->companyBusinessUnitPluginExecutorMock
        );
    }

    /**
     * @dataProvider getCycleHierarchy
     *
     * @param array|null $hierarchy
     * @param int $entryBusinessUnitId
     *
     * @return void
     */
    public function testIsHierarchyCycleExistsFindsCycle(?array $hierarchy, int $entryBusinessUnitId): void
    {
        // Assign
        $expectedResult = true;
        $hierarchy = $this->createNodeHierarchy($hierarchy);

        // Act
        $actualResult = $this->companyBusinessUnitWriter->isHierarchyCycleExists($hierarchy, $entryBusinessUnitId);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getCycleHierarchy(): array
    {
        return [
            [
                [[1, 3], [2, 3], [3, 4], [4, 2]], 1, // [1, 2] -> 3 -> 4 -> 2
            ],
            [
                [[1, 3], [2, 3], [3, 4], [4, 2]], 2, // [1, 2] -> 3 -> 4 -> 2
            ],
            [
                [[1, 3], [2, 3], [3, 4], [4, 2]], 3, // [1, 2] -> 3 -> 4 -> 2
            ],
            [
                [[1, 3], [2, 3], [3, 4], [4, 2]], 4, // [1, 2] -> 3 -> 4 -> 2
            ],
            [
                [[1, 1]], 1, // 1 -> 1
            ],
        ];
    }

    /**
     * @dataProvider getCyclelessHierarchy
     *
     * @param array|null $hierarchy
     * @param int $entryBusinessUnitId
     *
     * @return void
     */
    public function testIsHierarchyCycleExistsReturnsFalseWhenNoCycleFound(?array $hierarchy, int $entryBusinessUnitId): void
    {
        // Assign
        $expectedResult = false;
        $hierarchy = $this->createNodeHierarchy($hierarchy);

        // Act
        $actualResult = $this->companyBusinessUnitWriter->isHierarchyCycleExists($hierarchy, $entryBusinessUnitId);

        // Assert
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getCyclelessHierarchy(): array
    {
        return [
            [
                [[1, 3], [2, 3], [3, 4], [4, null]], 1, // [1, 2] -> 3 -> 4
            ],
            [
                [[1, 3], [2, 3], [3, 4], [4, null]], 2, // [1, 2] -> 3 -> 4
            ],
            [
                [[1, 3], [2, 3], [3, 4], [4, null]], 3, // [1, 2] -> 3 -> 4
            ],
            [
                [[1, 3], [2, 3], [3, 4], [4, null]], 4, // [1, 2] -> 3 -> 4
            ],
            [
                [[1, null]], 1,
            ],
        ];
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param int $parentCompanyBusinessUnitId
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createNode($idCompanyBusinessUnit, $parentCompanyBusinessUnitId): CompanyBusinessUnitTransfer
    {
        return (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
            ->setFkParentCompanyBusinessUnit($parentCompanyBusinessUnitId);
    }

    /**
     * @param array $hierarchy Elements are arrays in [ id => parentId ] structure
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer[]
     */
    protected function createNodeHierarchy(array $hierarchy): array
    {
        $nodeHierarchy = [];

        foreach ($hierarchy as $node) {
            $nodeHierarchy[$node[0]] = $this->createNode($node[0], $node[1]);
        }

        return $nodeHierarchy;
    }
}
