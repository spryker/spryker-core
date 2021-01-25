<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Locale\Business\LocaleFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Business
 * @group Facade
 * @group GlossaryFacadeTest
 * Add your own group annotations below this line
 */
class GlossaryFacadeTest extends Unit
{
    public const GLOSSARY_KEY = 'glossary_key';
    protected const TRANSLATION = 'translation';

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var \SprykerTest\Zed\Glossary\GlossaryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->getAvailableLocales();
    }

    /**
     * @return void
     */
    private function getAvailableLocales(): void
    {
        $this->locales = $this->getLocaleFacade()->getAvailableLocales();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacade
     */
    private function getLocaleFacade(): LocaleFacade
    {
        return new LocaleFacade();
    }

    /**
     * @return \Spryker\Zed\Glossary\Business\GlossaryFacade
     */
    private function getGlossaryFacade(): GlossaryFacade
    {
        return new GlossaryFacade();
    }

    /**
     * @param array $locales
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    private function buildLocaleTransferObject(array $locales): LocaleTransfer
    {
        $locale = new LocaleTransfer();
        $locale->setIdLocale($locales[0]);
        $locale->setLocaleName($this->locales[$locales[0]]);
        $locale->setIsActive(true);

        return $locale;
    }

    /**
     * @return void
     */
    public function testAddTranslation(): void
    {
        $glossaryFacade = $this->getGlossaryFacade();

        $formData = [
            self::GLOSSARY_KEY => 'form.button.save',
        ];
        foreach ($this->locales as $localeId => $localeName) {
            $formData['locales'][$localeName] = 'save ' . $localeId;
        }

        $translationTransfer = (new KeyTranslationTransfer())->fromArray($formData);

        $action = $glossaryFacade->saveGlossaryKeyTranslations($translationTransfer);

        $this->assertTrue($action);
    }

    /**
     * @return void
     */
    public function testUpdateTranslation(): void
    {
        $glossaryFacade = $this->getGlossaryFacade();
        $localesIds = array_keys($this->locales);

        $locale = $this->buildLocaleTransferObject($localesIds);

        $formData = [
            self::GLOSSARY_KEY => 'form.button.save',
        ];
        foreach ($this->locales as $localeId => $localeName) {
            $formData['locales'][$localeName] = 'save ' . $localeId;
        }

        $translationTransfer = (new KeyTranslationTransfer())->fromArray($formData);

        $glossaryFacade->saveGlossaryKeyTranslations($translationTransfer);

        $translatedKey = $glossaryFacade->getTranslation($formData[self::GLOSSARY_KEY], $locale);

        $changedLocales = [];
        foreach ($this->locales as $localeId => $localeName) {
            $changedLocales[$localeName] = 'save-changed-' . $localeId;
        }

        $translationTransfer->setLocales($changedLocales);

        $glossaryFacade->saveGlossaryKeyTranslations($translationTransfer);
        $translatedKeyChanged = $glossaryFacade->getTranslation($formData[self::GLOSSARY_KEY], $locale);

        $this->assertNotSame($translatedKey->getValue(), $translatedKeyChanged->getValue());
    }

    /**
     * @return void
     */
    public function testTranslationsCanBeFoundInBulk(): void
    {
        //Arrange
        $glossaryFacade = $this->getGlossaryFacade();
        $localeFacade = $this->getLocaleFacade();
        $localeTransfers = $localeFacade->getLocaleCollection();
        $seedData = ['glossaryKey' => static::GLOSSARY_KEY];
        foreach ($localeTransfers as $localeTransfer) {
            $seedData['locales'][$localeTransfer->getLocaleName()] = static::TRANSLATION;
        }
        $this->tester->haveTranslation($seedData);

        //Act
        $translations = $glossaryFacade->getTranslationsByGlossaryKeyAndLocales(static::GLOSSARY_KEY, $localeTransfers);

        //Assert
        $this->assertCount(count($localeTransfers), $translations);
    }

    /**
     * @return void
     */
    public function testTranslationsCanBeFoundInBuGlossaryKeysAndLocaleTransfers(): void
    {
        //Arrange
        $glossaryFacade = $this->getGlossaryFacade();
        $localeFacade = $this->getLocaleFacade();
        $localeTransfers = $localeFacade->getLocaleCollection();
        $seedData = ['glossaryKey' => static::GLOSSARY_KEY];
        foreach ($localeTransfers as $localeTransfer) {
            $seedData['locales'][$localeTransfer->getLocaleName()] = static::TRANSLATION;
        }
        $this->tester->haveTranslation($seedData);

        //Act
        $translations = $glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers([static::GLOSSARY_KEY], $localeTransfers);

        //Assert
        $this->assertCount(count($localeTransfers), $translations);
    }

    /**
     * @return void
     */
    public function testGlossaryKeyTransfersCanBeFoundByGlossaryKeysInBulk(): void
    {
        //Arrange
        $glossaryFacade = $this->getGlossaryFacade();
        $seedData = ['glossaryKey' => static::GLOSSARY_KEY];
        $this->tester->haveTranslation($seedData);

        //Act
        $translations = $glossaryFacade->getGlossaryKeyTransfersByGlossaryKeys([static::GLOSSARY_KEY]);

        //Assert
        $this->assertCount(1, $translations);
    }
}
