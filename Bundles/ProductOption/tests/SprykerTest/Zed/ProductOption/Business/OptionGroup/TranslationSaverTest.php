<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface;
use SprykerTest\Zed\ProductOption\Business\MockProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group OptionGroup
 * @group TranslationSaverTest
 * Add your own group annotations below this line
 */
class TranslationSaverTest extends MockProvider
{
    /**
     * @return void
     */
    public function testAddValueTranslationsShouldTranslateProvidedValues()
    {
        $translationSaver = $this->createAddTranslationSaverWithMocks();

        $productOptionGroupTransfer = new ProductOptionGroupTransfer();

        $productOptionTranslationTransfer = new ProductOptionTranslationTransfer();
        $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);

        $productOptionTranslationTransfer = new ProductOptionTranslationTransfer();
        $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);

        $translationSaver->addValueTranslations($productOptionGroupTransfer);
    }

    /**
     * @return void
     */
    public function testAddGroupNameTranslationsShouldTranslateProvidedValues()
    {
        $translationSaver = $this->createAddTranslationSaverWithMocks();

        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('name');

        $productOptionTranslationTransfer = new ProductOptionTranslationTransfer();
        $productOptionGroupTransfer->addGroupNameTranslation($productOptionTranslationTransfer);

        $productOptionTranslationTransfer = new ProductOptionTranslationTransfer();
        $productOptionGroupTransfer->addGroupNameTranslation($productOptionTranslationTransfer);

        $translationSaver->addGroupNameTranslations($productOptionGroupTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteTranslationShouldTriggerGlossaryDeleteRequest()
    {
        $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        $glossaryFacadeMock
            ->expects($this->once())
            ->method('deleteKey');

        $translationSaver = $this->createTranslationSaver($glossaryFacadeMock);

        $translationSaver->deleteTranslation('translation key');
    }

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface|null $glossaryFacadeMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface|null $localeFacadeMock
     *
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver
     */
    protected function createTranslationSaver(
        ?ProductOptionToGlossaryFacadeInterface $glossaryFacadeMock = null,
        ?ProductOptionToLocaleFacadeInterface $localeFacadeMock = null
    ) {
        if (!$glossaryFacadeMock) {
            $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        }

        if (!$localeFacadeMock) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        return new TranslationSaver(
            $glossaryFacadeMock,
            $localeFacadeMock
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver
     */
    protected function createAddTranslationSaverWithMocks()
    {
        $glossaryFacadeMock = $this->createGlossaryFacadeMock();
        $glossaryFacadeMock->expects($this->exactly(2))
            ->method('hasKey')
            ->willReturn(true);

        $glossaryFacadeMock->expects($this->exactly(2))
            ->method('hasTranslation')
            ->willReturn(false);

        $glossaryFacadeMock->expects($this->exactly(2))
            ->method('createAndTouchTranslation');

        $localeFacadeMock = $this->createLocaleFacadeMock();
        $localeFacadeMock->expects($this->exactly(2))
            ->method('getLocaleByCode')
            ->willReturn(new LocaleTransfer());

        $translationSaver = $this->createTranslationSaver($glossaryFacadeMock, $localeFacadeMock);

        return $translationSaver;
    }
}
