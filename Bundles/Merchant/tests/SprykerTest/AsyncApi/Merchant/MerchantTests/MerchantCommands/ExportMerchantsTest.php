<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Merchant\MerchantTests\MerchantCommands;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Zed\Event\Business\EventFacade;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeBridge;
use Spryker\Zed\Merchant\MerchantDependencyProvider;
use SprykerTest\AsyncApi\Merchant\AsyncApiTester;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group Merchant
 * @group MerchantTests
 * @group MerchantCommands
 * @group ExportMerchantsTest
 * Add your own group annotations below this line
 */
class ExportMerchantsTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var \SprykerTest\AsyncApi\Merchant\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testExportMerchantsMessageTriggersEventFacadeTriggerBulkMethodWithMerchantsToExport(): void
    {
        $merchantTransfersTheExportWasTriggeredFor = [];

        // Mock the event facade to catch calls to triggerBulk
        $eventFacadeMock = Stub::make(EventFacade::class, [
            'triggerBulk' => function (string $eventName, array $transfers) use (&$merchantTransfersTheExportWasTriggeredFor): void {
                $merchantTransfersTheExportWasTriggeredFor = $transfers;
            },
        ]);

        $this->tester->setDependency(MerchantDependencyProvider::FACADE_EVENT, new MerchantToEventFacadeBridge($eventFacadeMock));

        $exportMerchantsTransfer = $this->tester->haveExportMerchantsTransfer();

        // Act
        $this->tester->runMessageReceiveTest($exportMerchantsTransfer, 'merchant-commands');

        // Assert
        $this->assertTrue(count($merchantTransfersTheExportWasTriggeredFor) > 0, 'Expected that at least one merchant gets exported but none was exported.');
    }
}
