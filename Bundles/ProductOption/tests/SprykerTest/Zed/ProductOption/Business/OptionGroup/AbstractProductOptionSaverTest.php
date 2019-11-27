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
 *
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
    protected const VALUE_ID_PRODUCT_OPTION_GROUP = 1;
    protected const VALUE_SKU_PRODUCT_ABSTRACT = '123';

    /**
     * @return void
     */
    public function testAddProductAbstractToProductOptionGroupShouldAddProductToExistingGroup(): void
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

        $isUpdated = $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup(static::VALUE_SKU_PRODUCT_ABSTRACT, static::VALUE_ID_PRODUCT_OPTION_GROUP);

        $this->assertTrue($isUpdated);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractToProductOptionShouldThrowExceptionWhenGroupDoesNotExist(): void
    {
        $this->expectException(ProductOptionGroupNotFoundException::class);

        $productOptionGroupSaverMock = $this->createAbstractProductOptionSaver();

        $productOptionGroupSaverMock->expects($this->once())
            ->method('getOptionGroupById')
            ->with(static::VALUE_ID_PRODUCT_OPTION_GROUP)
            ->willThrowException(new ProductOptionGroupNotFoundException());

        $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup(static::VALUE_SKU_PRODUCT_ABSTRACT, static::VALUE_ID_PRODUCT_OPTION_GROUP);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractToProductOptionShouldThrowExceptionWhenAbstractProductDoesNotExists(): void
    {
        $this->expectException(AbstractProductNotFoundException::class);

        $productOptionGroupEntityMock = $this->createProductOptionGroupEntityMock();
        $productOptionGroupSaverMock = $this->createAbstractProductOptionSaver();

        $productOptionGroupSaverMock->expects($this->once())
            ->method('getOptionGroupById')
            ->willReturn($productOptionGroupEntityMock);

        $productOptionGroupSaverMock->expects($this->once())
            ->method('getProductAbstractBySku')
            ->with(static::VALUE_SKU_PRODUCT_ABSTRACT)
            ->willThrowException(new AbstractProductNotFoundException());

        $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup(static::VALUE_SKU_PRODUCT_ABSTRACT, static::VALUE_ID_PRODUCT_OPTION_GROUP);
    }

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface|null $productOptionContainerMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface|null $touchFacadeMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface|null $eventFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function createProductOptionGroupEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionGroup::class)
            ->setMethods(['save'])
            ->getMock();
    }
}
