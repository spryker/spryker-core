<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Locale\Negotiator;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Locale
 * @group Negotiator
 * @group LanguageNegotiatorTest
 * Add your own group annotations below this line
 */
class LanguageNegotiatorTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\Locale\LocaleGlueTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeThrowsExceptionIfStoreDoesNotHaveLocaleCodes(): void
    {
        //Arrange
        $languageNegotiator = $this->tester->createLanguageNegotiator(
            $this->createLocaleClientMock(),
            $this->tester->getLocator()->locale()->service(),
            $this->createStoreClientMock(new StoreTransfer()),
        );

        //Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($this->tester::EXCEPTION_MESSAGE);

        //Act
        $languageNegotiator->getLanguageIsoCode($this->tester::DE_LOCALE);
    }

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeReturnsIsoCodeByAcceptLanguageHeader(): void
    {
        //Arrange
        $storeTransfer = (new StoreTransfer())
            ->setName($this->tester::DE_STORE_NAME)
            ->setDefaultLocaleIsoCode($this->tester::EN_LOCALE);

        $languageNegotiator = $this->tester->createLanguageNegotiator(
            $this->createLocaleClientMock($this->tester::DEFAULT_DE_STORE_LOCALES),
            $this->tester->getLocator()->locale()->service(),
            $this->createStoreClientMock($storeTransfer),
        );

        //Act
        $languageIsoCode = $languageNegotiator->getLanguageIsoCode($this->tester::DE_ACCEPT_LANGUAGE_HEADER);

        //Assert
        $this->assertSame($this->tester::DE_LOCALE, $languageIsoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeReturnsIsoCodeIfAcceptHeaderMatchesStoreLocales(): void
    {
        //Arrange
        $storeTransfer = (new StoreTransfer())
            ->setName($this->tester::DE_STORE_NAME)
            ->setDefaultLocaleIsoCode($this->tester::EN_LOCALE);

        $languageNegotiator = $this->tester->createLanguageNegotiator(
            $this->createLocaleClientMock($this->tester::DEFAULT_DE_STORE_LOCALES),
            $this->tester->getLocator()->locale()->service(),
            $this->createStoreClientMock($storeTransfer),
        );

        //Act
        $languageIsoCode = $languageNegotiator->getLanguageIsoCode($this->tester::DE_LOCALE);

        //Assert
        $this->assertSame($this->tester::DE_LOCALE, $languageIsoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeReturnsDefaultStoreLocaleIsoCodeIfAcceptHeaderIsNotProvided(): void
    {
        //Arrange
        $storeTransfer = (new StoreTransfer())
            ->setName($this->tester::DE_STORE_NAME)
            ->setDefaultLocaleIsoCode($this->tester::EN_LOCALE);

        $languageNegotiator = $this->tester->createLanguageNegotiator(
            $this->createLocaleClientMock($this->tester::DEFAULT_DE_STORE_LOCALES),
            $this->tester->getLocator()->locale()->service(),
            $this->createStoreClientMock($storeTransfer),
        );

        //Act
        $languageIsoCode = $languageNegotiator->getLanguageIsoCode();

        //Assert
        $this->assertSame($this->tester::EN_LOCALE, $languageIsoCode);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    protected function createStoreClientMock(StoreTransfer $storeTransfer): LocaleToStoreClientInterface
    {
        $storeClientMock = $this->getMockBuilder(LocaleToStoreClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storeClientMock->method('getCurrentStore')
            ->willReturn($storeTransfer);

        return $storeClientMock;
    }

    /**
     * @param array<string, string> $storeLocales
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Locale\LocaleClientInterface
     */
    protected function createLocaleClientMock(array $storeLocales = []): LocaleClientInterface
    {
        $localeClientMock = $this->getMockBuilder(LocaleClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $localeClientMock->method('getLocales')
            ->willReturn($storeLocales);

        return $localeClientMock;
    }
}
