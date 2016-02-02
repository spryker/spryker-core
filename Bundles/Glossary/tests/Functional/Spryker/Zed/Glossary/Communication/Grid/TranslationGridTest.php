<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Glossary\Communication\Grid;

use Codeception\TestCase\Test;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer;
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
     * @var \Orm\Zed\Glossary\Persistence\Base\SpyGlossaryKeyQuery
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

        $queryContainer = $this->getGlossaryQueryContainer();
        $this->query = $queryContainer->queryKeysAndTranslationsForEachLanguage(array_keys($this->locales));
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
     * @return \Spryker\Zed\Locale\Business\LocaleFacade
     */
    private function getLocaleFacade()
    {
        return new LocaleFacade();
    }

    /**
     * @return \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer
     */
    private function getGlossaryQueryContainer()
    {
        return new GlossaryQueryContainer();
    }

}
