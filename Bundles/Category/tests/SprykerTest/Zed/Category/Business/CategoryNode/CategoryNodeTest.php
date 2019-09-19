<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\CategoryNode;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use ReflectionMethod;
use Spryker\Zed\Category\Business\CategoryBusinessFactory;
use Spryker\Zed\Category\Business\Model\CategoryToucher;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchBridge;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group CategoryNode
 * @group CategoryNodeTest
 * Add your own group annotations below this line
 */
class CategoryNodeTest extends Unit
{
    public const CATEGORY_ID_COMPUTER = 5;
    public const CATEGORY_ID_TABLETS = 8;

    public const CATEGORY_NODE_ID_ROOT = 1;
    public const CATEGORY_NODE_ID_CAMERAS_CAMCORDERS = 2;
    public const CATEGORY_NODE_ID_CAMCORDERS = 3;
    public const CATEGORY_NODE_ID_DIGITAL_CAMERAS = 4;
    public const CATEGORY_NODE_ID_COMPUTER = 5;
    public const CATEGORY_NODE_ID_NOTEBOOKS = 6;
    public const CATEGORY_NODE_ID_PC_WORKSTATIONS = 7;
    public const CATEGORY_NODE_ID_TABLETS = 8;

    /**
     * @return void
     */
    public function testUpdatingNodeTouchesEntireTreeBranch()
    {
        $toucherMock = $this->createCategoryToucherMock(['touchCategoryNodeActive']);
        $toucherMock
            ->expects($this->exactly(5))
            ->method('touchCategoryNodeActive')
            ->withConsecutive(
                [$this->equalTo(static::CATEGORY_NODE_ID_NOTEBOOKS)],       // Child
                [$this->equalTo(static::CATEGORY_NODE_ID_PC_WORKSTATIONS)], // Child
                [$this->equalTo(static::CATEGORY_NODE_ID_TABLETS)],         // Child
                [$this->equalTo(static::CATEGORY_NODE_ID_ROOT)],            // Root (Demoshop)
                [$this->equalTo(static::CATEGORY_NODE_ID_COMPUTER)]         // Self
            );

        $categoryNodeModel = $this->createCategoryNodeModel($toucherMock);

        $categoryTransfer = $this->createCategoryTransfer(static::CATEGORY_ID_COMPUTER);
        $categoryTransfer = $categoryNodeModel->read(static::CATEGORY_ID_COMPUTER, $categoryTransfer);

        $categoryNodeModel->update($categoryTransfer);
    }

    /**
     * @return void
     */
    public function testMovingNodeTouchesFormerParentNode()
    {
        $touchedIds = [];
        $expectedTouchedIds = [
            static::CATEGORY_NODE_ID_CAMERAS_CAMCORDERS,    // parent
            static::CATEGORY_NODE_ID_ROOT,                  // root
            static::CATEGORY_NODE_ID_TABLETS,               // self
            static::CATEGORY_NODE_ID_ROOT,                  // root
            static::CATEGORY_NODE_ID_COMPUTER,              // parent
        ];

        $toucherMock = $this->createCategoryToucherMock(['touchCategoryNodeActive']);
        $toucherMock
            ->expects($this->exactly(5))
            ->method('touchCategoryNodeActive')
            ->will($this->returnCallback(
                function ($idTouched) use (&$touchedIds) {
                    $touchedIds[] = $idTouched;
                }
            ));

        $categoryNodeModel = $this->createCategoryNodeModel($toucherMock);

        $categoryTransfer = $this->createCategoryTransfer(static::CATEGORY_ID_TABLETS);
        $categoryTransfer = $categoryNodeModel->read(static::CATEGORY_ID_TABLETS, $categoryTransfer);

        $categoryTransfer->getParentCategoryNode()->setIdCategoryNode(static::CATEGORY_NODE_ID_CAMERAS_CAMCORDERS);
        $categoryNodeModel->update($categoryTransfer);

        $diff = array_diff($touchedIds, $expectedTouchedIds);
        $this->assertCount(0, $diff, 'More category nodes touched as expected! Additionally touched category nodes: ' . implode(',', $diff));

        $diff = array_diff($expectedTouchedIds, $touchedIds);
        $this->assertCount(0, $diff, 'The following category nodes were expected to be touched but aren\'t: ' . implode(',', $diff));
    }

    /**
     * @param string[] $methodsToMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Category\Business\Model\CategoryToucher
     */
    protected function createCategoryToucherMock(array $methodsToMock)
    {
        return $this
            ->getMockBuilder(CategoryToucher::class)
            ->setConstructorArgs([
                $this->createTouchFacade(),
                $this->createCategoryQueryContainer(),
            ])
            ->setMethods($methodsToMock)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
     */
    protected function createTouchFacade()
    {
        return $this
            ->getMockBuilder(CategoryToTouchBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainer
     */
    protected function createCategoryQueryContainer()
    {
        return new CategoryQueryContainer();
    }

    /**
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucher $categoryToucher
     *
     * @return \Spryker\Zed\Category\Business\Model\CategoryNode\CategoryNode
     */
    protected function createCategoryNodeModel($categoryToucher)
    {
        $factory = $this->createCategoryBusinessFactory($categoryToucher);

        $methodReflection = new ReflectionMethod($factory, 'createCategoryNode');
        $methodReflection->setAccessible(true);

        return $methodReflection->invoke($factory);
    }

    /**
     * @param \Spryker\Zed\Category\Business\Model\CategoryToucher $categoryToucher
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Category\Business\CategoryBusinessFactory
     */
    protected function createCategoryBusinessFactory($categoryToucher)
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Category\Business\CategoryBusinessFactory $factoryMock */
        $factoryMock = $this
            ->getMockBuilder(CategoryBusinessFactory::class)
            ->setMethods(['createCategoryToucher'])
            ->getMock();

        $factoryMock
            ->method('createCategoryToucher')
            ->will($this->returnValue($categoryToucher));

        $container = new Container();
        $dependencyProvider = new CategoryDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $factoryMock->setContainer($container);

        $factoryMock->setQueryContainer($this->createCategoryQueryContainer());

        return $factoryMock;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function createCategoryTransfer($idCategory)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setIdCategory($idCategory);
        $categoryTransfer->setIsActive(true);

        return $categoryTransfer;
    }
}
