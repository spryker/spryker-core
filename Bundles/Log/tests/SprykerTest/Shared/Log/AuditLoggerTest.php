<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Shared\Log\AuditLoggerTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Log
 * @group AuditLoggerTest
 * Add your own group annotations below this line
 */
class AuditLoggerTest extends Unit
{
    use AuditLoggerTrait;

    /**
     * @return void
     */
    public function testGetAuditLoggerReturnsDefaultLoggerWhenLoggerIsNotFoundForChannel(): void
    {
        $this->assertInstanceOf(
            LoggerInterface::class,
            $this->getAuditLogger((new AuditLoggerConfigCriteriaTransfer())->setChannelName('test')),
        );
    }
}
