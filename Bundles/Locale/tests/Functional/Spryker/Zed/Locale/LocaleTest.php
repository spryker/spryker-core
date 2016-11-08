<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Locale;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Locale
 * @group LocaleTest
 */
class LocaleTest extends Test
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

    /**
     * @return void
     */
    public function testGetLocaleByIdReturnsValidLocaleTransfer()
    {
        $localeEntity = new SpyLocale();
        $localeEntity->setLocaleName('aa_AA');
        $localeEntity->save();

        $localeTransfer = $this->localeFacade->getLocaleById($localeEntity->getIdLocale());

        $this->assertInstanceOf(LocaleTransfer::class, $localeTransfer);
        $this->assertSame($localeEntity->getLocaleName(), $localeTransfer->getLocaleName());
    }

}
