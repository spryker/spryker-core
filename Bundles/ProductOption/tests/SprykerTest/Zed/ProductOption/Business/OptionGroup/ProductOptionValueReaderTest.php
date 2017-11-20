<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReader;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group ProductOptionValueReaderTest
 * Add your own group annotations below this line
 */
class ProductOptionValueReaderTest extends MockProvider
{
    /**
     * @return void
     */
    public function testGetProductOptionShouldReturnPersistedValueTransfer()
    {
        $productOptionValueReaderMock = $this->createProductOptionValueReader();

        $productOptionValueEntityMock = $this->createProductOptionValueEntityMock();

        $productOptionValueEntityMock->expects($this->once())
            ->method('getSpyProductOptionGroup')
            ->willReturn(new SpyProductOptionGroup());

        $productOptionValueReaderMock->method('getOptionValueById')
            ->willReturn($productOptionValueEntityMock);

        $productOptionTransfer = $productOptionValueReaderMock->getProductOption(1);

        $this->assertInstanceOf(ProductOptionTransfer::class, $productOptionTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductOptionWhenValueDoesNotExistShouldThrowException()
    {
        $this->expectException(ProductOptionNotFoundException::class);

        $productOptionValueReaderMock = $this->createProductOptionValueReader();

        $productOptionValueReaderMock->method('getOptionValueById')
            ->willReturn(null);

        $productOptionValueReaderMock->getProductOption(1);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReader
     */
    protected function createProductOptionValueReader()
    {
        $productOptionQueryContainerMock = $this->createProductOptionQueryContainerMock();

        return $this->getMockBuilder(ProductOptionValueReader::class)
            ->setConstructorArgs([$productOptionQueryContainerMock])
            ->setMethods(['getOptionValueById'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionValue::class)
            ->setMethods(['save', 'getSpyProductOptionGroup'])
            ->getMock();
    }
}
