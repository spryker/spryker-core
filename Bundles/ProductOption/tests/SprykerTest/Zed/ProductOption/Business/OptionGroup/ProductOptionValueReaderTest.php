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
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceReader;
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
     * @uses ProductOptionValueReader::findOptionValueById()
     * @uses SpyProductOptionValue::getSpyProductOptionGroup()
     *
     * @return void
     */
    public function testGetProductOptionReturnsPersistedValueTransfer()
    {
        // Assign
        $expectedIdProductOptionValue = 5;

        $productOptionValueReaderMock = $this->createProductOptionValueReader();
        $productOptionValueEntityMock = $this->createProductOptionValueEntityMock();
        $productOptionValueEntityMock->setIdProductOptionValue($expectedIdProductOptionValue);

        $productOptionValueEntityMock
            ->expects($this->any())
            ->method('getSpyProductOptionGroup')
            ->willReturn((new SpyProductOptionGroup()));
        $productOptionValueReaderMock
            ->expects($this->any())
            ->method('findOptionValueById')
            ->willReturn($productOptionValueEntityMock);

        // Act
        $actualProductOptionTransfer = $productOptionValueReaderMock->getProductOption($expectedIdProductOptionValue);

        // Assert
        $this->assertInstanceOf(ProductOptionTransfer::class, $actualProductOptionTransfer);
        $this->assertEquals($expectedIdProductOptionValue, $actualProductOptionTransfer->getIdProductOptionValue());
    }

    /**
     * @uses ProductOptionValueReader::findOptionValueById()
     *
     * @return void
     */
    public function testGetProductOptionThrowsExceptionWhenOptionValueWasNotFoundInPersistentStorage()
    {
        // Assign
        $dummyIdProductOptionValue = 1;
        $productOptionValueReaderMock = $this->createProductOptionValueReader();
        $productOptionValueReaderMock
            ->expects($this->any())
            ->method('findOptionValueById')
            ->willReturn(null);

        // Assert
        $this->expectException(ProductOptionNotFoundException::class);

        // Act
        $productOptionValueReaderMock->getProductOption($dummyIdProductOptionValue);
    }

    /**
     * @uses ProductOptionValueReader::findOptionValueById()
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReader
     */
    protected function createProductOptionValueReader()
    {
        $productOptionQueryContainerMock = $this->createProductOptionQueryContainerMock();
        $productOptionValuePriceReaderMock = $this->createProductOptionValuePriceReaderMock();

        return $this->getMockBuilder(ProductOptionValueReader::class)
            ->setConstructorArgs(
                [
                    $productOptionValuePriceReaderMock,
                    $productOptionQueryContainerMock,
                ]
            )
            ->setMethods(['findOptionValueById'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceReaderInterface
     */
    protected function createProductOptionValuePriceReaderMock()
    {
        return $this->getMockBuilder(ProductOptionValuePriceReader::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @uses SpyProductOptionValue::save()
     * @uses SpyProductOptionValue::getSpyProductOptionGroup()
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionValue::class)
            ->setMethods(['save', 'getSpyProductOptionGroup'])
            ->getMock();
    }
}
