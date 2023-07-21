<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Merchant\MerchantTests\MerchantCommands;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCreatedTransfer;
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
 * @group MerchantCreatedTest
 * Add your own group annotations below this line
 */
class MerchantCreatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\Merchant\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testMerchantCreatedMessageIsEmittedWhenMerchantsAreCreated(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
        ]);

        $merchantTransfer = $this->tester->haveMerchantWithStore();

        $merchantPublisherConfigTransfer = (new MerchantPublisherConfigTransfer())
            ->setMerchantIds([$merchantTransfer->getIdMerchant()])
            ->setEventName(MerchantCreatedTransfer::class);

        $merchantCreatedTransfer = new MerchantCreatedTransfer();
        $merchantCreatedTransfer->setMerchant($merchantTransfer);

        // Act
        $this->tester->getFacade()->emitPublishMerchantToMessageBroker($merchantPublisherConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($merchantCreatedTransfer, 'merchant-events');
    }
}
