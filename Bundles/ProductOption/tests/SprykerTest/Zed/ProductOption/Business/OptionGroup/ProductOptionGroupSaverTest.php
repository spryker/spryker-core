<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use ArrayObject;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface;
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
 * @group ProductOptionGroupSaverTest
 * Add your own group annotations below this line
 */
class ProductOptionGroupSaverTest extends MockProvider
{
    /**
     * @return void
     */
    public function testSaveProductOptionGroupShouldSaveGroup()
    {
        $translationSaverMock = $this->createTranslationSaverMock();
        $translationSaverMock->expects($this->once())
            ->method('addGroupNameTranslations');

        $translationSaverMock->expects($this->once())
            ->method('addValueTranslations');

        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver(
            null,
            null,
            $translationSaverMock
        );

        $optionGroupEntityMock = $this->createProductOptionGroupEntityMock();
        $optionGroupEntityMock->method('save')->willReturnCallback(function () use ($optionGroupEntityMock) {
            $optionGroupEntityMock->setIdProductOptionGroup(1);
        });

        $productOptionGroupSaverMock->expects($this->once())
            ->method('createProductOptionGroupEntity')
            ->willReturn($optionGroupEntityMock);

        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('TestGroup');
        $productOptionGroupTransfer->setFkTaxSet(1);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('value123');
        $productOptionValueTransfer->setPrices(new ArrayObject());
        $productOptionValueTransfer->setSku('sku123');

        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productOptionValueTranslationTransfer = new ProductOptionTranslationTransfer();
        $productOptionValueTranslationTransfer->setName('Name');
        $productOptionValueTranslationTransfer->setKey('Key');
        $productOptionValueTranslationTransfer->setLocaleCode('DE');

        $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionValueTranslationTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([1, 2, 3]);

        $idOfPersistedGroup = $productOptionGroupSaverMock->saveProductOptionGroup($productOptionGroupTransfer);

        $this->assertEquals($idOfPersistedGroup, 1);
    }

    /**
     * @return void
     */
    public function testToggleActiveShouldPersistCorrectActiveFlag()
    {
        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver();

        $productOptionGroupEntityMock = $this->createProductOptionGroupEntityMock();

        $productOptionGroupEntityMock->expects($this->once())
            ->method('save')
            ->willReturn(1);

        $productOptionGroupSaverMock->method('getOptionGroupById')
            ->willReturn($productOptionGroupEntityMock);

        $isActivated = $productOptionGroupSaverMock->toggleOptionActive(1, 1);

        $this->assertTrue($isActivated);
    }

    /**
     * @return void
     */
    public function testToggleActiveShouldThrowExceptionWhenGroupNotFound()
    {
        $this->expectException(ProductOptionGroupNotFoundException::class);

        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver();

        $productOptionGroupSaverMock->method('getOptionGroupById')
            ->willReturn(null);

        $productOptionGroupSaverMock->toggleOptionActive(1, 1);
    }

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface|null $productOptionContainerMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface|null $touchFacadeMock
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface|null $translationSaverMock
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface|null $productOptionValueSaverMock
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface|null $abstractProductOptionSaver
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaver
     */
    protected function createProductOptionGroupSaver(
        ?ProductOptionQueryContainerInterface $productOptionContainerMock = null,
        ?ProductOptionToTouchFacadeInterface $touchFacadeMock = null,
        ?TranslationSaverInterface $translationSaverMock = null,
        ?ProductOptionValueSaverInterface $productOptionValueSaverMock = null,
        ?AbstractProductOptionSaverInterface $abstractProductOptionSaver = null
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

        if (!$productOptionValueSaverMock) {
            $productOptionValueSaverMock = $this->createProductOptionValueSaverMock();
        }

        if (!$abstractProductOptionSaver) {
            $abstractProductOptionSaver = $this->createAbstractOptionGroupSaverMock();
        }

        return $this->getMockBuilder(ProductOptionGroupSaver::class)
            ->setConstructorArgs([
                $productOptionContainerMock,
                $touchFacadeMock,
                $translationSaverMock,
                $abstractProductOptionSaver,
                $productOptionValueSaverMock,
            ])
            ->setMethods([
                'getProductAbstractBySku',
                'getOptionGroupById',
                'getProductOptionValueById',
                'createProductOptionGroupEntity',
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
