<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Shared\Kernel\Store;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Store
 */
class StoreTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Store
     */
    private $Store;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->Store = Store::getInstance();

        $locales = $this->Store->getLocales();
        if (!in_array('de_DE', $locales)) {
            $this->markTestSkipped('These tests require `de_DE` as part of the current whitelisted locales.');
            return;
        }

        $this->Store->setCurrentLocale('de_DE');
    }

    /**
     * @return void
     */
    public function testInstance()
    {
        $this->assertInstanceOf('\Spryker\Shared\Kernel\Store', $this->Store);
    }

    /**
     * @return void
     */
    public function testGetLocales()
    {
        $locales = $this->Store->getLocales();
        $this->assertSame($locales['de'], 'de_DE');
    }

    /**
     * @return void
     */
    public function testSetCurrentLocale()
    {
        $locale = $this->Store->getCurrentLocale();
        $this->assertSame('de_DE', $locale);

        $newLocale = 'fr_FR';
        $this->Store->setCurrentLocale($newLocale);

        $locale = $this->Store->getCurrentLocale();
        $this->assertSame($newLocale, $locale);
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testSetCurrentLocaleInvalid()
    {
        $newLocale = 'xy_XY';
        $this->Store->setCurrentLocale($newLocale);
    }

}
