<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Tax\Business\OptionGroup;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Unit\Spryker\Zed\ProductOption\MockProvider;

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
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface $glossaryFacadeMock
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface $localeFacadeMock
     *
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver
     */
    protected function createTranslationSaver(
        ProductOptionToGlossaryInterface $glossaryFacadeMock = null,
        ProductOptionToLocaleInterface $localeFacadeMock = null
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
