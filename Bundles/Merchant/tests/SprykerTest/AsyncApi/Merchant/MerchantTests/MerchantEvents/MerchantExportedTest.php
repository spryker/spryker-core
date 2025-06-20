<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Merchant\MerchantTests\MerchantCommands;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantExportedTransfer;
use Generated\Shared\Transfer\MerchantPublisherConfigTransfer;
use SprykerTest\AsyncApi\Merchant\AsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group Merchant
 * @group MerchantTests
 * @group MerchantCommands
 * @group MerchantExportedTest
 * Add your own group annotations below this line
 */
class MerchantExportedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\Merchant\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testMerchantExportedMessageIsEmittedWhenMerchantsAreExported(): void
    {
        // Arrange
        $this->tester->setupMessageBroker(MerchantExportedTransfer::class, 'merchant-commands');
        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
        ]);

        $merchantTransfer = $this->tester->haveMerchantWithStore();

        $merchantPublisherConfigTransfer = (new MerchantPublisherConfigTransfer())
            ->setMerchantIds([$merchantTransfer->getIdMerchant()])
            ->setEventName(MerchantExportedTransfer::class);

        $merchantExportedTransfer = new MerchantExportedTransfer();
        $merchantExportedTransfer->setMerchant($merchantTransfer);

        // Act
        $this->tester->getFacade()->emitPublishMerchantToMessageBroker($merchantPublisherConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($merchantExportedTransfer, 'merchant-events');
    }
}
