<?php

namespace Functional\SprykerFeature\Zed\Glossary\Communication\Grid;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Glossary\Communication\Grid\TranslationGrid;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Glossary
 * @group TranslationGrid
 */
class TranslationGridTest extends Test
{

    /**
     * @var query
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
        return Locator::getInstance()->locale()->facade();
    }

    /**
     * @return GlossaryQueryContainer
     */
    private function getGlossaryQueryContainer()
    {
        $locator = Locator::getInstance();

        return $locator->glossary()->queryContainer();
    }

    public function testRenderedDataShouldContainFormColumnsWithNameOfGivenLocales()
    {
        $grid = new TranslationGrid($this->query, $this->request, array_keys($this->locales));

        $gridData = $grid->renderData();

        $localeIds = array_keys($this->locales);

        $this->assertSame($localeIds[0], $gridData['content']['columns'][$localeIds[0]]['name']);
        $this->assertSame($localeIds[1], $gridData['content']['columns'][$localeIds[1]]['name']);
    }

    public function testRenderedDataShouldContainColumnResultsWithNameOfGivenLocales()
    {
        $grid = new TranslationGrid($this->query, $this->request, array_keys($this->locales));

        $gridData = $grid->renderData();

        $localeIds = array_keys($this->locales);

        $this->assertArrayHasKey('translation_' . $localeIds[0] . '_value', $gridData['content']['rows'][0]);
        $this->assertArrayHasKey('translation_' . $localeIds[1] . '_value', $gridData['content']['rows'][1]);
    }
}
