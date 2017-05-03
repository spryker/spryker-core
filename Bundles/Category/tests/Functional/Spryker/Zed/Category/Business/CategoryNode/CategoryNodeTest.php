<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Category\Business\CategoryNode;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryTransfer;
use ReflectionMethod;
use Spryker\Zed\Category\Business\CategoryBusinessFactory;
use Spryker\Zed\Category\Business\Model\CategoryToucher;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Dependency\Facade\CategoryToTouchBridge;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Business
 * @group CategoryNode
 * @group CategoryNodeTest
 */
class CategoryNodeTest extends Test
{

    const CATEGORY_ID_COMPUTER = 5;
    const CATEGORY_ID_TABLETS = 8;

    const CATEGORY_NODE_ID_ROOT = 1;
    const CATEGORY_NODE_ID_CAMERAS_CAMCORDERS = 2;
    const CATEGORY_NODE_ID_CAMCORDERS = 3;
    const CATEGORY_NODE_ID_DIGITAL_CAMERAS = 4;
    const CATEGORY_NODE_ID_COMPUTER = 5;
    const CATEGORY_NODE_ID_NOTEBOOKS = 6;
    const CATEGORY_NODE_ID_PC_WORKSTATIONS = 7;
    const CATEGORY_NODE_ID_TABLETS = 8;

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
        $toucherMock = $this->createCategoryToucherMock(['touchCategoryNodeActive']);
        $toucherMock
            ->expects($this->exactly(5))
            ->method('touchCategoryNodeActive')
            ->withConsecutive(
                // New tree
                [$this->equalTo(static::CATEGORY_NODE_ID_CAMERAS_CAMCORDERS)],  // Parent
                [$this->equalTo(static::CATEGORY_NODE_ID_ROOT)],                // Root
                [$this->equalTo(static::CATEGORY_NODE_ID_TABLETS)],             // Self

                // Former tree
                [$this->equalTo(static::CATEGORY_NODE_ID_ROOT)],                // Root
                [$this->equalTo(static::CATEGORY_NODE_ID_COMPUTER)]             // Parent
            );

        $categoryNodeModel = $this->createCategoryNodeModel($toucherMock);

        $categoryTransfer = $this->createCategoryTransfer(static::CATEGORY_ID_TABLETS);
        $categoryTransfer = $categoryNodeModel->read(static::CATEGORY_ID_TABLETS, $categoryTransfer);

        $categoryTransfer->getParentCategoryNode()->setIdCategoryNode(static::CATEGORY_NODE_ID_CAMERAS_CAMCORDERS);
        $categoryNodeModel->update($categoryTransfer);
    }

    /**
     * @param string[] $methodsToMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Category\Business\Model\CategoryToucher
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Category\Dependency\Facade\CategoryToTouchInterface
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Category\Business\CategoryBusinessFactory
     */
    protected function createCategoryBusinessFactory($categoryToucher)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Category\Business\CategoryBusinessFactory $factoryMock */
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
