<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Locale;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainerInterface;

class LocaleTest extends Test
{

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->localeQueryContainer = new LocaleQueryContainer();
    }

    /**
     * @group Locale
     *
     * @return void
     */
    public function testCreateLocaleInsertsSomething()
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName('xy_ab');
        $this->assertEquals(0, $localeQuery->count());

        $this->localeFacade->createLocale('xy_ab');

        $this->assertEquals(1, $localeQuery->count());
    }

    /**
     * @group Locale
     *
     * @return void
     */
    public function testDeleteLocaleDeletesSoftly()
    {
        $localeQuery = $this->localeQueryContainer->queryLocaleByName('ab_xy');
        $this->localeFacade->createLocale('ab_xy');

        $this->assertTrue($localeQuery->findOne()->getIsActive());
        $this->localeFacade->deleteLocale('ab_xy');
        $this->assertFalse($localeQuery->findOne()->getIsActive());
    }

}
