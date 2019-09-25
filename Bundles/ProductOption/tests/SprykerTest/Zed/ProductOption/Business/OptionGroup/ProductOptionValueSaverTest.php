<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaver;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group ProductOptionValueSaverTest
 * Add your own group annotations below this line
 */
class ProductOptionValueSaverTest extends MockProvider
{
    /**
     * @uses ProductOptionValueSaver::createProductOptionValueEntity()
     * @uses SpyProductOptionValue::save()
     *
     * @return void
     */
    public function testSaveProductOptionValuePersistsProvidedOptionValue()
    {
        // Assign
        $productOptionValueEntity = $this->createProductOptionValueEntityMock();

        $productOptionValueSaver = $this->createProductOptionValueSaver();
        $productOptionValueSaver
            ->expects($this->any())
            ->method('createProductOptionValueEntity')
            ->willReturn($productOptionValueEntity);

        $productOptionValueTransfer = (new ProductOptionValueTransfer())
            ->setFkProductOptionGroup(1)
            ->setSku('testing_sku')
            ->setPrices(new ArrayObject([new MoneyValueTransfer()]))
            ->setValue('value');

        // Assert
        $productOptionValueEntity->expects($this->once())
            ->method('save');

        // Act
        $productOptionValueSaver->saveProductOptionValue($productOptionValueTransfer);
    }

    /**
     * @uses SpyProductOptionValue::save()
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionValue::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @uses ProductOptionValueSaver::createProductOptionValueEntity()
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    protected function createProductOptionValueSaver()
    {
        return $this->getMockBuilder(ProductOptionValueSaver::class)
            ->setConstructorArgs([
                $this->createProductOptionValuePriceSaverMock(),
                $this->createProductOptionQueryContainerMock(),
                $this->createTouchFacadeMock(),
                $this->createTranslationSaverMock(),
            ])
            ->setMethods(['createProductOptionValueEntity'])
            ->getMock();
    }
}
