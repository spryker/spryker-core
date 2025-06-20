<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Merchant\MerchantTests\MerchantCommands;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantPublisherConfigTransfer;
use Generated\Shared\Transfer\MerchantUpdatedTransfer;
use SprykerTest\AsyncApi\Merchant\AsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group Merchant
 * @group MerchantTests
 * @group MerchantCommands
 * @group MerchantUpdatedTest
 * Add your own group annotations below this line
 */
class MerchantUpdatedTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\Merchant\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testMerchantUpdatedMessageIsEmittedWhenMerchantsAreUpdated(): void
    {
        // Arrange
        $this->tester->setupMessageBroker(MerchantUpdatedTransfer::class, 'merchant-events');
        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
        ]);

        $merchantTransfer = $this->tester->haveMerchantWithStore();

        $merchantPublisherConfigTransfer = (new MerchantPublisherConfigTransfer())
            ->setMerchantIds([$merchantTransfer->getIdMerchant()])
            ->setEventName(MerchantUpdatedTransfer::class);

        $merchantUpdatedTransfer = new MerchantUpdatedTransfer();
        $merchantUpdatedTransfer->setMerchant($merchantTransfer);

        // Act
        $this->tester->getFacade()->emitPublishMerchantToMessageBroker($merchantPublisherConfigTransfer);

        // Assert
        $this->tester->assertMessageWasEmittedOnChannel($merchantUpdatedTransfer, 'merchant-events');
    }
}
