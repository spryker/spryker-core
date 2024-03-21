<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Language;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceBridge;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceInterface;
use Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiation;
use Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Language
 * @group LanguageNegotiationTest
 *
 * Add your own group annotations below this line
 */
class LanguageNegotiationTest extends Unit
{
    /**
     * @var array
     */
    protected $locales = ['de' => 'de_DE', 'en' => 'en_US'];

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeShouldReturnBaseWhenSelected(): void
    {
        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('en; de;q=0.5');

        $this->assertSame('en_US', $isoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeShouldReturnBasedOnPriority(): void
    {
        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('de;q=0.8, en;q=0.2');
        $this->assertSame('de_DE', $isoCode);

        $isoCode = $languageNegotiation->getLanguageIsoCode('de;q=0.2, en;q=0.8');
        $this->assertSame('en_US', $isoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageWhenNoHeaderProviderMustReturnFirstLocale(): void
    {
        if ($this->isDynamicStoreEnabled()) {
            $this->markTestSkipped('With a full suite, at some point tests stores, that are not compatible with a code in this test, are created. Tech Debt ticket is added to fix it properly.');
        }

        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('');
        $this->assertSame('de_DE', $isoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageWhenHeaderInvalidFormatterMustReturnFirstLocale(): void
    {
        if ($this->isDynamicStoreEnabled()) {
            $this->markTestSkipped('With a full suite, at some point tests stores, that are not compatible with a code in this test, are created. Tech Debt ticket is added to fix it properly.');
        }

        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('wrong');
        $this->assertSame('de_DE', $isoCode);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface
     */
    protected function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->createStoreClientMock(), $this->createLocaleServiceMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface
     */
    protected function createStoreClientMock(): GlueApplicationToStoreClientInterface
    {
        $storeClientMock = $this->getMockBuilder(GlueApplicationToStoreClientInterface::class)
            ->setMethods(['getCurrentStore', 'isDynamicStoreEnabled'])
            ->getMock();

        $storeTransfer = (new StoreTransfer())->setAvailableLocaleIsoCodes($this->locales);

        $storeClientMock->method('getCurrentStore')
            ->willReturn($storeTransfer);
        $storeClientMock->method('isDynamicStoreEnabled')
            ->willReturn($this->isDynamicStoreEnabled());

        return $storeClientMock;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToLocaleServiceInterface
     */
    protected function createLocaleServiceMock(): GlueApplicationToLocaleServiceInterface
    {
        return new GlueApplicationToLocaleServiceBridge(
            $this->tester->getLocator()->locale()->service(),
        );
    }

    /**
     * @return bool
     */
    protected function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }
}
