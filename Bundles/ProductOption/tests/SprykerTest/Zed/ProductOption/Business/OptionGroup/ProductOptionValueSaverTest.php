<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
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
     * @return void
     */
    public function testSaveProductOptionValueShouldPersistProvidedOptionValue()
    {
        $productOptionGroupSaverMock = $this->createProductOptionValueSaver();

        $productOptionValueEntity = $this->createProductOptionValueEntityMock();

        $productOptionValueEntity->expects($this->once())
            ->method('save');

        $productOptionGroupSaverMock
            ->expects($this->once())
            ->method('createProductOptionValueEntity')
            ->willReturn($productOptionValueEntity);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup(1);
        $productOptionValueTransfer->setSku('testing_sku');
        $productOptionValueTransfer->setPrice(120);
        $productOptionValueTransfer->setValue('value');

        $productOptionGroupSaverMock->saveProductOptionValue($productOptionValueTransfer);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionValue::class)
            ->setMethods(['save'])
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface|null $productOptionContainerMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface|null $touchFacadeMock
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface|null $translationSaverMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    protected function createProductOptionValueSaver(
        ProductOptionQueryContainerInterface $productOptionContainerMock = null,
        ProductOptionToTouchInterface $touchFacadeMock = null,
        TranslationSaverInterface $translationSaverMock = null
    ) {

        if (!$productOptionContainerMock) {
            $productOptionContainerMock = $this->createProductOptionQueryContainerMock();
        }

        if (!$touchFacadeMock) {
            $touchFacadeMock = $this->createTouchFacadeMock();
        }

        if (!$translationSaverMock) {
            $translationSaverMock = $this->createTranslationSaverMock();
        }

        return $this->getMockBuilder(ProductOptionValueSaver::class)
            ->setConstructorArgs([
                $productOptionContainerMock,
                $touchFacadeMock,
                $translationSaverMock,
            ])
            ->setMethods([
                'getProductAbstractBySku',
                'getOptionGroupById',
                'createProductOptionValueEntity',
            ])
            ->getMock();

    }

}
