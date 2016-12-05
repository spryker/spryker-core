<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOption\Business\OptionGroup;

use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaver;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Unit\Spryker\Zed\ProductOption\MockProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group AbstractProductOptionSaverTest
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
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface|null $touchFacadeMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
     */
    protected function createAbstractProductOptionSaver(
        ProductOptionQueryContainerInterface $productOptionContainerMock = null,
        ProductOptionToTouchInterface $touchFacadeMock = null
    ) {

        if (!$productOptionContainerMock) {
            $productOptionContainerMock = $this->createProductOptionQueryContainerMock();
        }

        if (!$touchFacadeMock) {
            $touchFacadeMock = $this->createTouchFacadeMock();
        }

        return $this->getMockBuilder(AbstractProductOptionSaver::class)
            ->setConstructorArgs([
                $productOptionContainerMock,
                $touchFacadeMock,
            ])
            ->setMethods([
                'getProductAbstractBySku',
                'getOptionGroupById'
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
