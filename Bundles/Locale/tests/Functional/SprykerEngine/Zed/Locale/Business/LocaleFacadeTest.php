<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Locale\Business;

use Codeception\TestCase\Test;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistanceFactory;

/**
 * @group Locale
 */
class LocaleFacadeTest extends Test
{

    /**
     * @var \SprykerEngine\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var LocaleQueryContainerInterface
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

    protected function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();
        $this->localeFacade = new LocaleFacade(new Factory('Locale'), $locator);
        $this->localeQueryContainer = new LocaleQueryContainer(new PersistanceFactory('Locale'), $locator);
        $this->availableLocales = Store::getInstance()->getLocales();
        $this->localeNames = $this->localeFacade->getAvailableLocales();
    }

    public function testAvailableLocalesToBeArrayType()
    {
        $this->assertInternalType('array', $this->localeNames);
    }

    /**
     * @group TranslationGrid
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
     */
    public function testAvailableLocalesHasDifferentIdsThanConfiguredOnes()
    {
        $this->assertNotSame(
            array_keys($this->availableLocales),
            array_keys($this->localeNames)
        );
    }

}
