<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\PhpMd\Rules\Design;

use Codeception\Test\Unit;
use PHPMD\AbstractNode;
use Spryker\Zed\Development\Business\PhpMd\Rules\Design\CouplingBetweenObjects;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group PhpMd
 * @group Rules
 * @group Design
 * @group CouplingBetweenObjectsTest
 * Add your own group annotations below this line
 */
class CouplingBetweenObjectsTest extends Unit
{
    public const NUMBER_OF_COUPLING_BETWEEN_OBJECTS = 2;
    public const THRESHOLD = 1;

    /**
     * @dataProvider ignorableNodesProvider
     *
     * @param string $fullyQualifiedClassName
     * @param string $nodeName
     *
     * @return void
     */
    public function testApplyDoesNotAddViolationIfNodeIsIgnorable($fullyQualifiedClassName, $nodeName)
    {
        $nodeMock = $this->getNodeMock($fullyQualifiedClassName, $nodeName);

        $couplingBetweenObjectsMock = $this->getCouplingBetweenObjectsMock();
        $couplingBetweenObjectsMock->expects($this->never())->method('addViolation');
        $couplingBetweenObjectsMock->apply($nodeMock);
    }

    /**
     * @return array
     */
    public function ignorableNodesProvider()
    {
        return [
            ['Zed\Foo\BarDependencyProvider', 'BarDependencyProvider'],
            ['Zed\Foo\BarFactory', 'BarFactory'],
            ['Zip\Zap\YvesBootstrap', 'YvesBootstrap'],
            ['Zed\Zap\FooServiceProvider', 'FooServiceProvider'],
            ['Yves\Zap\FooServiceProvider', 'FooServiceProvider'],
            ['Client\Zap\FooServiceProvider', 'FooServiceProvider'],
        ];
    }

    /**
     * @return void
     */
    public function testApplyAddsViolationWhenClassIsNotIgnorable()
    {
        $nodeMock = $this->getNodeMock('Foo', 'Bar');

        $couplingBetweenObjectsMock = $this->getCouplingBetweenObjectsMock();
        $couplingBetweenObjectsMock->expects($this->once())->method('addViolation');
        $couplingBetweenObjectsMock->apply($nodeMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\PhpMd\Rules\Design\CouplingBetweenObjects
     */
    protected function getCouplingBetweenObjectsMock()
    {
        $mockBuilder = $this->getMockBuilder(CouplingBetweenObjects::class);
        $mockBuilder->setMethods(['addViolation', 'getIntProperty']);

        $couplingBetweenObjectsMock = $mockBuilder->getMock();
        $couplingBetweenObjectsMock->expects($this->once())->method('getIntProperty')->willReturn(static::THRESHOLD);

        return $couplingBetweenObjectsMock;
    }

    /**
     * @param string $fullyQualifiedClassName
     * @param string $nodeName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\PHPMD\AbstractNode
     */
    protected function getNodeMock($fullyQualifiedClassName, $nodeName)
    {
        $mockBuilder = $this->getMockBuilder(AbstractNode::class);
        $mockBuilder->setMethods(['getMetric', 'getName', 'getNamespace', 'getNamespaceName', 'hasSuppressWarningsAnnotationFor', 'getFullQualifiedName', 'getParentName'])
            ->disableOriginalConstructor();

        $nodeMock = $mockBuilder->getMock();
        $nodeMock->expects($this->once())->method('getMetric')->willReturn(static::NUMBER_OF_COUPLING_BETWEEN_OBJECTS);

        $nodeMock->method('getFullQualifiedName')->willReturn($fullyQualifiedClassName);
        $nodeMock->method('getName')->willReturn($nodeName);

        return $nodeMock;
    }
}
