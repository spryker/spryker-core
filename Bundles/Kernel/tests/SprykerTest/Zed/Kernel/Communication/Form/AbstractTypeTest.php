<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication\Form;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerTest\Zed\Kernel\Communication\Form\Fixtures\FooType;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group Form
 * @group AbstractTypeTest
 * Add your own group annotations below this line
 */
class AbstractTypeTest extends Unit
{
    /**
     * @return void
     */
    public function testGetCommunicationFactoryShouldReturnInstanceIfExists()
    {
        $formType = new FooType();

        $formTypeReflection = new ReflectionClass($formType);
        $communicationFactoryProperty = $formTypeReflection->getParentClass()->getProperty('factory');
        $communicationFactoryProperty->setAccessible(true);
        $abstractCommunicationFactoryMock = $this->getMockBuilder(AbstractCommunicationFactory::class)->disableOriginalConstructor()->getMock();
        $communicationFactoryProperty->setValue($formType, $abstractCommunicationFactoryMock);

        $communicationFactory = $formType->getFactory();

        $this->assertInstanceOf(AbstractCommunicationFactory::class, $communicationFactory);
    }

    /**
     * @return void
     */
    public function testGetFacadeShouldThrowExceptionIfFacadeNotFound()
    {
        $this->expectException(FacadeNotFoundException::class);

        $formType = new FooType();
        $formType->getFacade();
    }

    /**
     * @return void
     */
    public function testGetFacadeShouldReturnInstanceIfExists()
    {
        $formType = new FooType();

        $formTypeReflection = new ReflectionClass($formType);
        $facadeProperty = $formTypeReflection->getParentClass()->getProperty('facade');
        $facadeProperty->setAccessible(true);
        $abstractFacadeMock = $this->getMockBuilder(AbstractFacade::class)->disableOriginalConstructor()->getMock();
        $facadeProperty->setValue($formType, $abstractFacadeMock);

        $facade = $formType->getFacade();

        $this->assertInstanceOf(AbstractFacade::class, $facade);
    }

    /**
     * @return void
     */
    public function testGetQueryContainerThrowExceptionIfQueryContainerNotFound()
    {
        $this->expectException(QueryContainerNotFoundException::class);

        $queryContainerResolverMock = $this->getMockBuilder(QueryContainerResolver::class)->setMethods(['canResolve', 'getClassInfo'])->getMock();
        $queryContainerResolverMock->method('canResolve')->willReturn(false);

        $classInfo = new ClassInfo();
        $classInfo->setClass('\\Namespace\\Application\\Bundle\\Layer\\Foo\\Bar');
        $queryContainerResolverMock->method('getClassInfo')->willReturn($classInfo);

        $fooType = new FooType();

        $fooTypeReflection = new ReflectionClass($fooType);
        $getQueryContainerResolverMethod = $fooTypeReflection->getParentClass()->getMethod('getQueryContainerResolver');
        $getQueryContainerResolverMethod->setAccessible(true);
        $getQueryContainerResolverMethod->invoke($fooType, $queryContainerResolverMock);

        $fooType->getQueryContainer();
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnInstanceIfQueryContainerIfExists()
    {
        $formType = new FooType();

        $formTypeReflection = new ReflectionClass($formType);
        $queryContainerProperty = $formTypeReflection->getParentClass()->getProperty('queryContainer');
        $queryContainerProperty->setAccessible(true);
        $queryContainerProperty->setValue($formType, $this->getMockBuilder(AbstractQueryContainer::class)->disableOriginalConstructor()->getMock());

        $queryContainer = $formType->getQueryContainer();

        $this->assertInstanceOf(AbstractQueryContainer::class, $queryContainer);
    }
}
