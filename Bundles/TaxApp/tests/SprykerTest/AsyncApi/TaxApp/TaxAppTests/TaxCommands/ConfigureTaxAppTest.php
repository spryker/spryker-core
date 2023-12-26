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
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
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
    }

    /**
     * @return void
     */
    public function testWhenConfigureTaxAppMessageIsReceivedThenTheTaxAppIsConfigured(): void
    {
        // Arrange
        $storeTransfer = $this->tester->createStoreTransferWithStoreReference();
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod($storeTransfer);

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
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($configureTaxAppTransfer->getVendorCode(), $storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testWhenConfigureTaxAppMessageIsReceivedAndTenantIdentifierIsPresentThenTheTaxAppIsConfigured(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod($this->tester->haveStore());

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference('de-DE')
            ->setTenantIdentifier('tenant-identifier')
            ->setActorId('actor-id');

        $configureTaxAppTransfer = new ConfigureTaxAppTransfer();
        $configureTaxAppTransfer->setApiUrl('https://example.com')
            ->setVendorCode(Uuid::uuid4()->toString())
            ->setMessageAttributes($messageAttributesTransfer)
            ->setIsActive(true);

        // Act
        $this->tester->runMessageReceiveTest($configureTaxAppTransfer, 'tax-commands');

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($configureTaxAppTransfer->getVendorCode(), null);
    }

    /**
     * @return void
     */
    public function testWhenConfigureTaxAppMessageIsReceivedAndTenantIdentifierIsPresentButStoreReferenceIsNullThenTheTaxAppIsConfigured(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod($this->tester->haveStore());

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setTenantIdentifier('tenant-identifier')
            ->setActorId('actor-id');

        $configureTaxAppTransfer = new ConfigureTaxAppTransfer();
        $configureTaxAppTransfer->setApiUrl('https://example.com')
            ->setVendorCode(Uuid::uuid4()->toString())
            ->setMessageAttributes($messageAttributesTransfer)
            ->setIsActive(true);

        // Act
        $this->tester->runMessageReceiveTest($configureTaxAppTransfer, 'tax-commands');

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($configureTaxAppTransfer->getVendorCode(), null);
    }

    /**
     * @return void
     */
    public function testWhenConfigureTaxAppMessageIsReceivedAndNeitherEmitterOrActorIdArePresentThenAnExceptionIsThrown(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference('de-DE')
            ->setTenantIdentifier('tenant-identifier');

        $configureTaxAppTransfer = new ConfigureTaxAppTransfer();
        $configureTaxAppTransfer->setApiUrl('https://example.com')
            ->setVendorCode(Uuid::uuid4()->toString())
            ->setMessageAttributes($messageAttributesTransfer)
            ->setIsActive(true);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('actorId');

        // Act
        $this->tester->runMessageReceiveTest($configureTaxAppTransfer, 'tax-commands');
    }
}
