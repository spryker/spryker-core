<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\TaxApp\TaxAppTests\TaxCommands;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ConfigureTaxAppTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\AsyncApi\TaxApp\AsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group TaxApp
 * @group TaxAppTests
 * @group TaxCommands
 * @group ConfigureTaxAppTest
 * Add your own group annotations below this line
 */
class ConfigureTaxAppTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\TaxApp\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureTaxAppConfigTableIsEmpty();
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
    }

    /**
     * @return void
     */
    public function testWhenConfigureTaxAppMessageIsReceivedThenTheTaxAppIsConfigured(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference('de-DE')
            ->setEmitter('emitter');

        $configureTaxAppTransfer = new ConfigureTaxAppTransfer();
        $configureTaxAppTransfer->setApiUrl('https://example.com')
            ->setVendorCode(Uuid::uuid4()->toString())
            ->setMessageAttributes($messageAttributesTransfer)
            ->setIsActive(true);

        // Act
        $this->tester->runMessageReceiveTest($configureTaxAppTransfer, 'tax-commands');

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($configureTaxAppTransfer->getVendorCode());
    }
}
