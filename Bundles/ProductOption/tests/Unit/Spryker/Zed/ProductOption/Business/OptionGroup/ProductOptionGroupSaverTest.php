<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface;
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
 * @group ProductOptionGroupSaverTest
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

        $touchFacadeMock = $this->createTouchFacadeMock();

        $touchFacadeMock->expects($this->exactly(3))
            ->method('touchActive');

        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver(
            null,
            $touchFacadeMock,
            $translationSaverMock
        );

        $optionGroupEntityMock = $this->createProductOptionGroupEntityMock();
        $optionGroupEntityMock->method('save')->willReturnCallback(function () use ($optionGroupEntityMock) {
            $optionGroupEntityMock->setIdProductOptionGroup(1);
        });

        $productOptionGroupSaverMock->expects($this->once())
            ->method('createProductOptionGroupEntity')
            ->willReturn($optionGroupEntityMock);

        $productOptionGroupSaverMock->expects($this->once())
            ->method('createProductOptionValueEntity')
            ->willReturn($this->createProductOptionValueEntityMock());

        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('TestGroup');
        $productOptionGroupTransfer->setFkTaxSet(1);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('value123');
        $productOptionValueTransfer->setPrice(120);
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
     * @return void
     */
    public function testAddProductAbstractToProductOptionGroupShouldAddProductToExistingGroup()
    {
        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())
            ->method('touchActive');

        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver(null, $touchFacadeMock);

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

        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver();

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

        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver();
        $productOptionGroupSaverMock->expects($this->once())
            ->method('getOptionGroupById')
            ->willReturn($productOptionGroupEntityMock);

        $productOptionGroupSaverMock->addProductAbstractToProductOptionGroup('123', 1);
    }

    /**
     * @return void
     */
    public function testSaveProductOptionValueShouldPersistProvidedOptionValue()
    {
        $productOptionGroupSaverMock = $this->createProductOptionGroupSaver();

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
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface|null $productOptionContainerMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface|null $touchFacadeMock
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface|null $translationSaverMock
     *
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaver|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createProductOptionGroupSaver(
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

        return $this->getMockBuilder(ProductOptionGroupSaver::class)
            ->setConstructorArgs([$productOptionContainerMock, $touchFacadeMock, $translationSaverMock])
            ->setMethods([
                'getProductAbstractBySku',
                'getOptionGroupById',
                'getProductOptionValueById',
                'createProductOptionGroupEntity',
                'createProductOptionValueEntity'
            ])
            ->getMock();

    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver
     */
    protected function createTranslationSaverMock()
    {
        return $this->getMockBuilder(TranslationSaver::class)
            ->disableOriginalConstructor()
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntityMock()
    {
        return $this->getMockBuilder(SpyProductOptionValue::class)
            ->setMethods(['save'])
            ->getMock();
    }

}
