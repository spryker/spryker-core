<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaver;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group AbstractProductOptionSaverTest
 * Add your own group annotations below this line
 */
class AbstractProductOptionSaverTest extends MockProvider
{
    /**
     * @return void
     */
    public function testAddProductAbstractToProductOptionGroupShouldAddProductToExistingGroup()
    {
        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())
            ->method('touchActive');

        $productOptionGroupSaverMock = $this->createAbstractProductOptionSaver(null, $touchFacadeMock);

        $productOptionGroupEntityMock = $this->createProductOptionGroupEntityMock();

        $productOptionGroupEntityMock->method('save')->willReturn(1);

        $productOptionGroupSaverMock->expects($this->once())
            ->method('getOptionGroupById')
            ->willReturn($productOptionGroupEntityMock);

        $productOptionGroupSaverMock->expects($this->once())
            ->method('getProductAbstractBySku')
            ->willReturn(new SpyProductAbstract());

        $isUpdated = $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup('123', 1);

        $this->assertTrue($isUpdated);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractToProductOptionShouldThrowExceptionWhenGroupDoesNotExist()
    {
        $this->expectException(ProductOptionGroupNotFoundException::class);

        $productOptionGroupSaverMock = $this->createAbstractProductOptionSaver();

        $productOptionGroupSaverMock->expects($this->once())
            ->method('getOptionGroupById')
            ->willReturn(null);

        $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup('123', 1);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractToProductOptionShouldThrowExceptionWhenAbstractProductDoesNotExists()
    {
        $this->expectException(AbstractProductNotFoundException::class);

        $productOptionGroupEntityMock = $this->createProductOptionGroupEntityMock();

        $productOptionGroupSaverMock = $this->createAbstractProductOptionSaver();
        $productOptionGroupSaverMock->expects($this->once())
            ->method('getOptionGroupById')
            ->willReturn($productOptionGroupEntityMock);

        $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup('123', 1);
    }

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface|null $productOptionContainerMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface|null $touchFacadeMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface|null $eventFacadeMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
     */
    protected function createAbstractProductOptionSaver(
        ?ProductOptionQueryContainerInterface $productOptionContainerMock = null,
        ?ProductOptionToTouchFacadeInterface $touchFacadeMock = null,
        ?ProductOptionToEventFacadeInterface $eventFacadeMock = null
    ) {

        if (!$productOptionContainerMock) {
            $productOptionContainerMock = $this->createProductOptionQueryContainerMock();
        }

        if (!$touchFacadeMock) {
            $touchFacadeMock = $this->createTouchFacadeMock();
        }
        if (!$eventFacadeMock) {
            $eventFacadeMock = $this->createEventFacadeMock();
        }

        return $this->getMockBuilder(AbstractProductOptionSaver::class)
            ->setConstructorArgs([
                $productOptionContainerMock,
                $touchFacadeMock,
                $eventFacadeMock,
            ])
            ->setMethods([
                'getProductAbstractBySku',
                'getOptionGroupById',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function createProductOptionGroupEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionGroup::class)
            ->setMethods(['save'])
            ->getMock();
    }
}
