<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use ReflectionMethod;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilder;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitTreeBuilder
 * @group CompanyBusinessUnitTreeBuilderTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitTreeBuilderTest extends Unit
{
    protected const LEVEL = 'level';
    protected const CHILDREN = 'children';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $repositoryMock;

    /**
     * @var \ArrayObject
     */
    protected $companyBusinessUnits;

    /**
     * @var array
     */
    protected $companyBusinessUnitTreeArray = [
        1 => [
            self::LEVEL => 0,
            self::CHILDREN => [
                2 => [
                    self::LEVEL => 1,
                    self::CHILDREN => null,
                ],
                3 => [
                    self::LEVEL => 1,
                    self::CHILDREN => null,
                ],
            ],
        ],
        4 => [
            self::LEVEL => 0,
            self::CHILDREN => [
                5 => [
                    self::LEVEL => 1,
                    self::CHILDREN => [
                        6 => [
                            self::LEVEL => 2,
                            self::CHILDREN => null,
                        ],
                    ],
                ],
                7 => [
                    self::LEVEL => 1,
                    self::CHILDREN => null,
                ],
            ],
        ],
    ];

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->getMockBuilder(CompanyBusinessUnitRepositoryInterface::class)->getMock();
        $this->initCompanyBusinessUnits();
    }

    /**
     * @return void
     */
    public function testTreeBuilderCanBuildCorrectTree(): void
    {
        $companyBusinessUnitTreeBuilder = new ReflectionMethod(
            '\Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder\CompanyBusinessUnitTreeBuilder',
            'buildTree'
        );

        $companyBusinessUnitTreeBuilder->setAccessible(true);

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeTransfer[]|\ArrayObject $companyBusinessUnitTreeNodes */
        $companyBusinessUnitTree = $companyBusinessUnitTreeBuilder->invoke(
            new CompanyBusinessUnitTreeBuilder($this->repositoryMock),
            $this->companyBusinessUnits,
            null,
            0
        );

        $companyBusinessUnitTreeMappedToArray = $this->mapTreeToArray($companyBusinessUnitTree);
        $this->assertEquals($this->companyBusinessUnitTreeArray, $companyBusinessUnitTreeMappedToArray);
    }

    /**
     * @param \ArrayObject $customerCompanyBusinessUnitTreeNodes
     *
     * @return array
     */
    protected function mapTreeToArray(ArrayObject $customerCompanyBusinessUnitTreeNodes): array
    {
        $companyBusinessUnitTreeNodes = [];
        foreach ($customerCompanyBusinessUnitTreeNodes as $companyBusinessUnitTreeNode) {
            $companyBusinessUnitTreeNodeArray = [];

            $companyBusinessUnitTreeNodeArray[static::LEVEL] = $companyBusinessUnitTreeNode->getLevel();

            $children = $this->mapTreeToArray($companyBusinessUnitTreeNode->getChildren());
            $idCompanyBusinessUnit = $companyBusinessUnitTreeNode->getCompanyBusinessUnit()->getIdCompanyBusinessUnit();

            $companyBusinessUnitTreeNodeArray[static::CHILDREN] = $children ?: null;
            $companyBusinessUnitTreeNodes[$idCompanyBusinessUnit] = $companyBusinessUnitTreeNodeArray;
        }

        return $companyBusinessUnitTreeNodes;
    }

    /**
     * @return void
     */
    protected function initCompanyBusinessUnits(): void
    {
        $this->companyBusinessUnits = new ArrayObject();

        /**
         * tree structure: null -> A -> B, C
         *
         * (null -> A -> B, C means: A is parent of B and C, A is doesn`t have parent)
         */
        $this->companyBusinessUnits->append($this->createBusinessUnit(1, 'A', null));
        $this->companyBusinessUnits->append($this->createBusinessUnit(2, 'B', 1));
        $this->companyBusinessUnits->append($this->createBusinessUnit(3, 'C', 1));

        /**
         * tree structure: null -> D -> E -> G ; D -> F
         */
        $this->companyBusinessUnits->append($this->createBusinessUnit(4, 'D', null));
        $this->companyBusinessUnits->append($this->createBusinessUnit(5, 'E', 4));
        $this->companyBusinessUnits->append($this->createBusinessUnit(6, 'G', 5));
        $this->companyBusinessUnits->append($this->createBusinessUnit(7, 'F', 4));
    }

    /**
     * @param int $companyBusinessUnitId
     * @param string $companyBusinessUnitName
     * @param int|null $fkParentCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createBusinessUnit(int $companyBusinessUnitId, string $companyBusinessUnitName, ?int $fkParentCompanyBusinessUnit): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer
            ->setIdCompanyBusinessUnit($companyBusinessUnitId)
            ->setName($companyBusinessUnitName)
            ->setFkParentCompanyBusinessUnit($fkParentCompanyBusinessUnit);

        return $companyBusinessUnitTransfer;
    }
}
