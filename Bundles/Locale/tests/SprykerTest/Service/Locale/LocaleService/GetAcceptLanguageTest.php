<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Locale\LocaleService;

use Codeception\Test\Unit;
use Spryker\Service\Locale\LocaleService;
use Spryker\Service\Locale\LocaleServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Locale
 * @group LocaleService
 * @group GetAcceptLanguageTest
 * Add your own group annotations below this line
 */
class GetAcceptLanguageTest extends Unit
{
    /**
     * @var \Spryker\Service\Locale\LocaleServiceInterface
     */
    protected LocaleServiceInterface $localeService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->localeService = new LocaleService();
    }

    /**
     * @dataProvider getAcceptLanguageDataProvider
     *
     * @param string $acceptLanguageHeader
     * @param list<string> $priorities
     * @param string|null $expected
     *
     * @return void
     */
    public function testShouldReturnExpectedAcceptLanguageType(string $acceptLanguageHeader, array $priorities, ?string $expected): void
    {
        // Act
        $acceptLanguageTransfer = $this->localeService->getAcceptLanguage($acceptLanguageHeader, $priorities);

        // Assert
        if (!$acceptLanguageTransfer) {
            $this->assertNull($acceptLanguageTransfer);

            return;
        }

        $this->assertSame($expected, $acceptLanguageTransfer->getType());
    }

    /**
     * @return list<array<string, list<string>, string|null>>
     */
    protected function getAcceptLanguageDataProvider(): array
    {
        return [
            ['en, de', ['fr'], null],
            ['foo, bar, yo', ['baz', 'biz'], null],
            ['fr-FR, en;q=0.8', ['en-US', 'de-DE'], 'en-us'],
            ['en, *;q=0.9', ['fr'], 'fr'],
            ['foo, bar, yo', ['yo'], 'yo'],
            ['en; q=0.1, fr; q=0.4, bu; q=1.0', ['en', 'fr'], 'fr'],
            ['en; q=0.1, fr; q=0.4, fu; q=0.9, de; q=0.2', ['en', 'fu'], 'fu'],
            ['fr, zh-Hans-CN;q=0.3', ['fr'], 'fr'],
            ['en;q=0.5,de', ['de;q=0.3', 'en;q=0.9'], 'en'],
            ['fr-FR, en-US;q=0.8', ['fr'], 'fr'],
            ['fr-FR, en-US;q=0.8', ['fr-CA', 'en'], 'en'],
        ];
    }
}
