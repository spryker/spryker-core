<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReader;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
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
    public function testGetProductOptionGroupByIdWhenOptionDoesNotExistShouldThrowException()
    {
        $this->expectException(ProductOptionGroupNotFoundException::class);

        $productGroupReaderMock = $this->createProductOptionGroupReader();
        $productGroupReaderMock->method('queryProductGroupById')->willReturn(null);

        $this->createProductOptionGroupReader()->getProductOptionGroupById(1);
    }

    /**
     * @return void
     */
    public function testGetProductOptionGroupByIdShouldReturnHydrateGroupTransfer()
    {
        $localeFacadeMock = $this->createLocaleFacadeMock();

        $localeFacadeMock
            ->expects($this->once())
            ->method('getLocaleCollection')->willReturn([new LocaleTransfer()]);

        $glossaryFacadeMock = $this->createGlossaryFacadeMock();

        $glossaryFacadeMock
            ->expects($this->exactly(2))
            ->method('getTranslation')->willReturn(new TranslationTransfer());

        $glossaryFacadeMock
            ->expects($this->exactly(2))
            ->method('hasTranslation')->willReturn(true);

        $productGroupReaderMock = $this->createProductOptionGroupReader(
            null,
            $glossaryFacadeMock,
            $localeFacadeMock
        );

        $productOptionGroupEntity = new SpyProductOptionGroup();
        $productOptionGroupEntity->setName('groupName');
        $productOptionGroupEntity->addSpyProductOptionValue(new SpyProductOptionValue());

        $productGroupReaderMock->method('queryProductGroupById')
            ->willReturn($productOptionGroupEntity);

        $productOptionGroupTransfer = $productGroupReaderMock->getProductOptionGroupById(1);

        $this->assertInstanceOf(ProductOptionGroupTransfer::class, $productOptionGroupTransfer);
    }

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface|null $productOptionContainerMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface|null $glossaryMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface|null $localeFacadeMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReader
     */
    protected function createProductOptionGroupReader(
        ProductOptionQueryContainerInterface $productOptionContainerMock = null,
        ProductOptionToGlossaryInterface $glossaryMock = null,
        ProductOptionToLocaleInterface $localeFacadeMock = null
    ) {

        if (!$glossaryMock) {
            $glossaryMock = $this->createGlossaryFacadeMock();
        }

        if (!$productOptionContainerMock) {
            $productOptionContainerMock = $this->createProductOptionQueryContainerMock();
        }

        if (!$localeFacadeMock) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        return $this->getMockBuilder(ProductOptionGroupReader::class)
            ->setConstructorArgs([$productOptionContainerMock, $glossaryMock, $localeFacadeMock])
            ->setMethods(['queryProductGroupById'])
            ->getMock();
    }

}
