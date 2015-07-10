<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Glossary\Communication\Grid;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Glossary\Communication\Grid\TranslationGrid;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainer;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Base\SpyGlossaryKeyQuery;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerFeature
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

    public function setUp()
    {
        parent::setUp();

        $this->generateTestLocales();

        $dependencyContainer = $this->getGlossaryQueryContainer();
        $this->query = $dependencyContainer->queryKeysAndTranslationsForEachLanguage(array_keys($this->locales));
        $this->request = Request::createFromGlobals();
    }

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
     * @return GlossaryQueryContainer
     */
    private function getGlossaryQueryContainer()
    {
        return $this->getLocator()->glossary()->queryContainer();
    }

    public function testRenderedDataShouldContainFormColumnsWithNameOfGivenLocales()
    {
        $grid = new TranslationGrid($this->query, $this->request, array_keys($this->locales));
        $gridData = $grid->renderData();

        $localeIds = array_keys($this->locales);

        $this->assertSame($localeIds[0], $gridData['content']['columns'][$localeIds[0]]['name']);
        $this->assertSame($localeIds[1], $gridData['content']['columns'][$localeIds[1]]['name']);
    }

//    public function testRenderedDataShouldContainColumnResultsWithNameOfGivenLocales()
    public function testRenderedGridDataShouldContainColumnsWhereIdOfGivenLocaleIsTheColumnKey()
    {
        $grid = new TranslationGrid($this->query, $this->request, array_keys($this->locales));
        $gridData = $grid->renderData();

        $localeIds = array_keys($this->locales);

        $this->assertArrayHasKey($localeIds[0], $gridData['content']['columns']);
        $this->assertArrayHasKey($localeIds[1], $gridData['content']['columns']);
    }

}
