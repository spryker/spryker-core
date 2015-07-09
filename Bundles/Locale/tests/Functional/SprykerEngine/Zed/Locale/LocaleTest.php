<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Locale;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainerInterface;

class LocaleTest extends Test
{

    /**
     * @var \SprykerEngine\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    protected function setUp()
    {
        parent::setUp();
        $locator = Locator::getInstance();
        $this->localeFacade = new LocaleFacade(new Factory('Locale'), $locator);
        $this->localeQueryContainer = new LocaleQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Locale'), $locator);
    }

    /**
     * @group Locale
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
