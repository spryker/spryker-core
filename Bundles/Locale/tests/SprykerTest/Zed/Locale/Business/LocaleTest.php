<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Persistence\LocaleRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Locale
 * @group Business
 * @group LocaleTest
 * Add your own group annotations below this line
 */
class LocaleTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_LOCALE_NAME = 'xy_ab';

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface
     */
    protected $localeRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->localeRepository = new LocaleRepository();
    }

    /**
     * @group Locale
     *
     * @return void
     */
    public function testCreateLocaleInsertsSomething(): void
    {
        //Arrange
        $localesCount = $this->localeRepository->getLocalesCountByLocaleName(static::TEST_LOCALE_NAME);
        $this->assertSame(0, $localesCount);

        //Act
        $this->localeFacade->createLocale(static::TEST_LOCALE_NAME);

        //Assert
        $localesCount = $this->localeRepository->getLocalesCountByLocaleName(static::TEST_LOCALE_NAME);
        $this->assertSame(1, $localesCount);
    }

    /**
     * @group Locale
     *
     * @return void
     */
    public function testDeleteLocaleDeletesSoftly(): void
    {
        //Arrange
        $this->localeFacade->createLocale(static::TEST_LOCALE_NAME);
        $this->localeFacade->deleteLocale(static::TEST_LOCALE_NAME);

        //Act
        $localeAfterDelete = $this->localeRepository->findLocaleByLocaleName(static::TEST_LOCALE_NAME);

        //Assert
        $this->assertFalse($localeAfterDelete->getIsActive());
    }

    /**
     * @return void
     */
    public function testGetLocaleByIdReturnsValidLocaleTransfer(): void
    {
        $localeEntity = new SpyLocale();
        $localeEntity->setLocaleName('aa_AA');
        $localeEntity->save();

        $localeTransfer = $this->localeFacade->getLocaleById($localeEntity->getIdLocale());

        $this->assertInstanceOf(LocaleTransfer::class, $localeTransfer);
        $this->assertSame($localeEntity->getLocaleName(), $localeTransfer->getLocaleName());
    }
}
