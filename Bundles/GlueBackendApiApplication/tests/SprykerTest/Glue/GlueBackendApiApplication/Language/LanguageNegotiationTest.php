<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\LanguageNegotiation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcceptLanguageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface;
use Spryker\Glue\GlueBackendApiApplication\Dependency\Service\GlueBackendApiApplicationToLocaleServiceInterface;
use Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiation;
use Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group LanguageNegotiation
 * @group LanguageNegotiationTest
 * Add your own group annotations below this line
 */
class LanguageNegotiationTest extends Unit
{
    /**
     * @var array<string, string>
     */
    protected const AVAILABLE_LOCALE_ISO_CODES = [
        'de' => 'de_DE',
        'en' => 'en_US',
    ];

    /**
     * @return \Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface
     */
    protected function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation(
            $this->createStoreFacadeMock(),
            $this->createLocaleServiceMock(),
        );
    }

    /**
     * @dataProvider languageIsoCodeDataProvider
     *
     * @param string $acceptLanguage
     * @param \Generated\Shared\Transfer\AcceptLanguageTransfer|null $foundAcceptLanguage
     * @param string $expectedLanguageIsoCode
     *
     * @return void
     */
    public function testGetLanguageIsoCode(string $acceptLanguage, ?AcceptLanguageTransfer $foundAcceptLanguage, string $expectedLanguageIsoCode): void
    {
        // Arrange
        $storeFacadeMock = $this->createStoreFacadeMock();
        $localeServiceMock = $this->createLocaleServiceMock($foundAcceptLanguage);

        $languageNegotiation = new LanguageNegotiation(
            $storeFacadeMock,
            $localeServiceMock,
        );

        // Act
        $languageIsoCode = $languageNegotiation->getLanguageIsoCode($acceptLanguage);

        // Assert
        $this->assertSame($expectedLanguageIsoCode, $languageIsoCode);
    }

    /**
     * @return array<string, array<string, string>>
     */
    protected function languageIsoCodeDataProvider(): array
    {
        return [
            'AcceptLanguageUnavailable' => [
                'acceptLanguage' => '',
                'foundAcceptLanguage' => null,
                'expectedLanguageIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES['de'],
            ],
            'AcceptLanguageInvalid' => [
                'acceptLanguage' => 'invalid',
                'foundAcceptLanguage' => null,
                'expectedLanguageIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES['de'],
            ],
            'AcceptLanguageNegotiable' => [
                'acceptLanguage' => 'de;q=0.8, en;q=0.2',
                'foundAcceptLanguage' => (new AcceptLanguageTransfer())->setType('de'),
                'expectedLanguageIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES['de'],
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueBackendApiApplication\Dependency\Facade\GlueBackendApiApplicationToStoreFacadeInterface
     */
    protected function createStoreFacadeMock(): GlueBackendApiApplicationToStoreFacadeInterface
    {
        $storeTransfer = $this->createStoreTransfer();
        $storeFacadeMock = $this->getMockBuilder(GlueBackendApiApplicationToStoreFacadeInterface::class)
            ->setMethods(['getCurrentStore'])
            ->getMock();

        $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);

        return $storeFacadeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer(): StoreTransfer
    {
        return (new StoreTransfer())->setAvailableLocaleIsoCodes(static::AVAILABLE_LOCALE_ISO_CODES);
    }

    /**
     * @param \Generated\Shared\Transfer\AcceptLanguageTransfer|null $foundAcceptLanguage
     *
     * @return \Spryker\Glue\GlueBackendApiApplication\Dependency\Service\GlueBackendApiApplicationToLocaleServiceInterface
     */
    protected function createLocaleServiceMock(?AcceptLanguageTransfer $foundAcceptLanguage): GlueBackendApiApplicationToLocaleServiceInterface
    {
        $localeServiceMock = $this->getMockBuilder(GlueBackendApiApplicationToLocaleServiceInterface::class)->getMock();

        $localeServiceMock->method('getAcceptLanguage')->willReturn($foundAcceptLanguage);

        return $localeServiceMock;
    }
}
