<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Log\Communication\Plugin\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Zed\Log\Communication\Plugin\Log\ZedSecurityAuditLoggerConfigPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Log
 * @group Communication
 * @group Plugin
 * @group Log
 * @group ZedSecurityAuditLoggerConfigPluginTest
 * Add your own group annotations below this line
 */
class ZedSecurityAuditLoggerConfigPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForSecurityChannel(): void
    {
        // Arrange
        $zedSecurityAuditLoggerConfigPlugin = new ZedSecurityAuditLoggerConfigPlugin();

        // Act
        $isApplicable = $zedSecurityAuditLoggerConfigPlugin->isApplicable(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName('security'),
        );

        // Assert
        $this->assertTrue($isApplicable);
    }
}
