<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Glossary\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Base\SpyGlossaryKeyQuery;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Glossary
 * @group GlossaryFacade
 * @group Business
 */
class GlossaryFacadeTest extends Test
{

    const GLOSSARY_KEY = 'glossary_key';

    /**
     * @var SpyGlossaryKeyQuery
     */
    private $query;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $locales = [];

    public function setUp()
    {
        parent::setUp();

        $this->getAvailableLocales();
    }

    private function getAvailableLocales()
    {
        $this->locales = $this->getLocaleFacade()->getAvailableLocales();
    }

    /**
     * @return LocaleFacade
     */
    private function getLocaleFacade()
    {
        return new LocaleFacade(new Factory('Locale'), $this->getLocator());
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return GlossaryFacade
     */
    private function getGlossaryFacade()
    {
        return $this->getLocator()->glossary()->facade();
    }

    /**
     * @param array $locales
     *
     * @return LocaleTransfer
     */
    private function buildLocaleTransferObject(array $locales)
    {
        $locale = new LocaleTransfer();
        $locale->setIdLocale($locales[0]);
        $locale->setLocaleName($this->locales[$locales[0]]);
        $locale->setIsActive(true);

        return $locale;
    }

    public function testAddTranslation()
    {
        $facade = $this->getGlossaryFacade();

        $formData = [
            self::GLOSSARY_KEY => 'form.button.save',
        ];
        foreach ($this->locales as $localeId => $localeName) {
            $formData['locales'][$localeName] = 'save ' . $localeId;
        }

        $translationTransfer = (new KeyTranslationTransfer())->fromArray($formData);

        $action = $facade->saveGlossaryKeyTranslations($translationTransfer);

        $this->assertTrue($action);
    }

    public function testUpdateTranslation()
    {
        $facade = $this->getGlossaryFacade();
        $localesIds = array_keys($this->locales);

        $locale = $this->buildLocaleTransferObject($localesIds);

        $formData = [
            self::GLOSSARY_KEY => 'form.button.save',
        ];
        foreach ($this->locales as $localeId => $localeName) {
            $formData['locales'][$localeName] = 'save ' . $localeId;
        }

        $translationTransfer = (new KeyTranslationTransfer())->fromArray($formData);

        $facade->saveGlossaryKeyTranslations($translationTransfer);

        $translatedKey = $facade->getTranslation($formData[self::GLOSSARY_KEY], $locale);

        $changedLocales = [];
        foreach ($this->locales as $localeId => $localeName) {
            $changedLocales[$localeName] = 'save-changed-' . $localeId;
        }

        $translationTransfer->setLocales($changedLocales);

        $facade->saveGlossaryKeyTranslations($translationTransfer);
        $translatedKeyChanged = $facade->getTranslation($formData[self::GLOSSARY_KEY], $locale);

        $this->assertNotSame($translatedKey->getValue(), $translatedKeyChanged->getValue());
    }
}
