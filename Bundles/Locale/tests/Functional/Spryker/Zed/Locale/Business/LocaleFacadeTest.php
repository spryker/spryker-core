<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Locale\Business;

use Codeception\TestCase\Test;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;

/**
 * @group Locale
 */
class LocaleFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var array
     */
    protected $availableLocales = [];

    /**
     * @var array
     */
    protected $localeNames = [];

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->localeQueryContainer = new LocaleQueryContainer();
        $this->availableLocales = Store::getInstance()->getLocales();
        $this->localeNames = $this->localeFacade->getAvailableLocales();
    }

    /**
     * @return void
     */
    public function testAvailableLocalesToBeArrayType()
    {
        $this->assertInternalType('array', $this->localeNames);
    }

    /**
     * @group TranslationGrid
     *
     * @return void
     */
    public function testAvailableLocalesAreTheSameAsConfiguredOnes()
    {
        $this->assertSame(
            array_values($this->availableLocales),
            array_values($this->localeNames)
        );
    }

    /**
     * @group TranslationGrid
     *
     * @return void
     */
    public function testAvailableLocalesHasDifferentIdsThanConfiguredOnes()
    {
        $this->assertNotSame(
            array_keys($this->availableLocales),
            array_keys($this->localeNames)
        );
    }

}
