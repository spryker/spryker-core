<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder;

use ArrayObject;
use Codeception\TestCase\Test;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionMethod;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilder;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitTreeBuilder
 * @group CompanyBusinessUnitTreeBuilderTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitTreeBuilderTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    /**
     * @dataProvider \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester::createCompanyBusinessUnitsProvider
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer[]|\ArrayObject $companyBusinessUnits
     * @param array $companyBusinessUnitTreeArray
     *
     * @return void
     */
    public function testTreeBuilderCanBuildCorrectTree(ArrayObject $companyBusinessUnits, array $companyBusinessUnitTreeArray): void
    {
        // Arrange
        $companyBusinessUnitTreeBuilder = new ReflectionMethod(
            '\Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilder',
            'buildTree'
        );
        $companyBusinessUnitTreeBuilder->setAccessible(true);
        $companyBusinessUnitRepositoryMock = $this->createCompanyBusinessUnitRepositoryMock();

        // Act
        $companyBusinessUnitTree = $companyBusinessUnitTreeBuilder->invoke(
            new CompanyBusinessUnitTreeBuilder($companyBusinessUnitRepositoryMock),
            $companyBusinessUnits,
            null,
            0
        );

        // Assert
        $this->assertEquals($companyBusinessUnitTreeArray, $this->tester->mapTreeToArray($companyBusinessUnitTree));
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCompanyBusinessUnitRepositoryMock(): MockObject
    {
        return $this->getMockBuilder(CompanyBusinessUnitRepositoryInterface::class)
            ->getMock();
    }
}
