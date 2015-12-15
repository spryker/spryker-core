<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Glossary\Communication\Grid;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer;
use Orm\Zed\Glossary\Persistence\Base\SpyGlossaryKeyQuery;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Spryker
 * @group Zed
 * @group Glossary
 * @group Business
 * @group TranslationGrid
 */
class TranslationGridTest extends Test
{

    /**
     * @var SpyGlossaryKeyQuery
     */
    private $query;

    /**
     * @var request
     */
    private $request;

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

        $this->generateTestLocales();

        $dependencyContainer = $this->getGlossaryQueryContainer();
        $this->query = $dependencyContainer->queryKeysAndTranslationsForEachLanguage(array_keys($this->locales));
        $this->request = Request::createFromGlobals();
    }

    /**
     * @return void
     */
    private function generateTestLocales()
    {
        $locale = $this->getLocaleFacade()->createLocale('xx_XX');
        $this->locales[$locale->getIdLocale()] = $locale->getLocaleName();

        $locale = $this->getLocaleFacade()->createLocale('xy_XY');
        $this->locales[$locale->getIdLocale()] = $locale->getLocaleName();
    }

    /**
     * @return LocaleFacade
     */
    private function getLocaleFacade()
    {
        return new LocaleFacade();
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return GlossaryQueryContainer
     */
    private function getGlossaryQueryContainer()
    {
        return $this->getLocator()->glossary()->queryContainer();
    }

}
