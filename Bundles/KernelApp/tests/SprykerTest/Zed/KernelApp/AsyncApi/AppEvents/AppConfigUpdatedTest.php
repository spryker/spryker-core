<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\KernelApp\KernelAppTests\AppEvents;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Zed\KernelApp\KernelAppAsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group KernelApp
 * @group KernelAppTests
 * @group AppEvents
 * @group AppConfigUpdatedTest
 * Add your own group annotations below this line
 */
class AppConfigUpdatedTest extends Unit
{
    use DataCleanupHelperTrait;

    /**
     * @var \SprykerTest\Zed\KernelApp\KernelAppAsyncApiTester
     */
    protected KernelAppAsyncApiTester $tester;

    /**
     * @return void
     */
    public function testGivenAnAppConfigIsPersistedAndTheMessageChangesTheIsActivePropertyWhenTheAppConfigUpdatedMessageIsHandledThenTheAppConfigIsUpdated(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();

        $appConfigTransfer = $this->tester->haveAppConfigPersisted([
            AppConfigUpdatedTransfer::APP_IDENTIFIER => $appIdentifier,
            AppConfigTransfer::IS_ACTIVE => false,
            AppConfigTransfer::CONFIG => ['foo' => 'bar'],
            AppConfigTransfer::STATUS => 'NEW',
        ]);

        $appConfigTransfer->setIsActive(true);

        $appConfigUpdatedTransfer = $this->tester->haveAppConfigUpdatedTransfer([
            AppConfigUpdatedTransfer::APP_IDENTIFIER => $appIdentifier,
            AppConfigTransfer::IS_ACTIVE => true,
            AppConfigTransfer::CONFIG => ['foo' => 'bar'],
            AppConfigTransfer::STATUS => 'NEW',
        ]);

        // Act
        $this->tester->runMessageReceiveTest($appConfigUpdatedTransfer, 'app-events');

        // Assert
        $this->tester->assertAppConfigIsPersisted($appIdentifier, $appConfigTransfer);
    }
}
