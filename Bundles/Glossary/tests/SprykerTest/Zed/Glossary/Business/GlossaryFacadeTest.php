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

    /**
     * @var array
     */
    private $locales = [];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->getAvailableLocales();
    }

    /**
     * @return void
     */
    private function getAvailableLocales()
    {
        $this->locales = $this->getLocaleFacade()->getAvailableLocales();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacade
     */
    private function getLocaleFacade()
    {
        return new LocaleFacade();
    }

    /**
     * @return \Spryker\Zed\Glossary\Business\GlossaryFacade
     */
    private function getGlossaryFacade()
    {
        return new GlossaryFacade();
    }

    /**
     * @param array $locales
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    private function buildLocaleTransferObject(array $locales)
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
    public function testAddTranslation()
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
    public function testUpdateTranslation()
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
}
