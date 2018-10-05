<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReader;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group ProductOptionGroupReaderTest
 * Add your own group annotations below this line
 */
class ProductOptionGroupReaderTest extends MockProvider
{
    /**
     * @return void
     */
    public function testGetProductOptionGroupByIdThrowsExceptionWhenOptionDoesNotExist()
    {
        // Assign
        $productGroupReader = new ProductOptionGroupReader(
            $this->createProductOptionValuePriceHydratorMock(),
            $this->getQueryContainerMock(),
            $this->createGlossaryFacadeMock(),
            $this->createLocaleFacadeMock()
        );

        // Assert
        $this->expectException(ProductOptionGroupNotFoundException::class);

        // Act
        $productGroupReader->getProductOptionGroupById(1);
    }

    /**
     * @uses LocaleFacadeInterface::getLocaleCollection()
     *
     * @return void
     */
    public function testGetProductOptionGroupByIdReturnsProductOptionGroupTransfer()
    {
        // Assign
        $localeFacadeMock = $this->createLocaleFacadeMock();
        $localeFacadeMock
            ->expects($this->any())
            ->method('getLocaleCollection')
            ->willReturn([ new LocaleTransfer()]);

        $productOptionGroupEntity = new SpyProductOptionGroup();

        $productGroupReader = new ProductOptionGroupReader(
            $this->createProductOptionValuePriceHydratorMock(),
            $this->getQueryContainerMock($productOptionGroupEntity),
            $this->createGlossaryFacadeMock(),
            $localeFacadeMock
        );

        // Act
        $productOptionGroupTransfer = $productGroupReader->getProductOptionGroupById(1);

        // Assert
        $this->assertInstanceOf(ProductOptionGroupTransfer::class, $productOptionGroupTransfer);
    }

    /**
     * @uses ProductOptionQueryContainerInterface::queryProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById()
     * @uses SpyProductOptionGroupQuery::find()
     *
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup|null $productOptionGroupEntity
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected function getQueryContainerMock(?SpyProductOptionGroup $productOptionGroupEntity = null)
    {
        $groupCollection = $this->getMockBuilder(ObjectCollection::class)->getMock();
        $groupCollection->expects($this->any())->method('getFirst')->willReturn($productOptionGroupEntity);

        $groupQuery = $this->getMockBuilder(SpyProductOptionGroupQuery::class)->getMock();
        $groupQuery->expects($this->any())->method('find')->willReturn($groupCollection);

        $queryContainerMock = $this->createProductOptionQueryContainerMock();
        $queryContainerMock
            ->expects($this->any())
            ->method('queryProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById')
            ->willReturn($groupQuery);

        return $queryContainerMock;
    }
}
