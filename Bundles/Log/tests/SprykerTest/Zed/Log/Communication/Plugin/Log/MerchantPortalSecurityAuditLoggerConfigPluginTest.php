<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Log\Communication\Plugin\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Zed\Log\Communication\Plugin\Log\MerchantPortalSecurityAuditLoggerConfigPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Log
 * @group Communication
 * @group Plugin
 * @group Log
 * @group MerchantPortalSecurityAuditLoggerConfigPluginTest
 * Add your own group annotations below this line
 */
class MerchantPortalSecurityAuditLoggerConfigPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForSecurityChannel(): void
    {
        // Arrange
        $merchantPortalSecurityAuditLoggerConfigPlugin = new MerchantPortalSecurityAuditLoggerConfigPlugin();

        // Act
        $isApplicable = $merchantPortalSecurityAuditLoggerConfigPlugin->isApplicable(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName('security'),
        );

        // Assert
        $this->assertTrue($isApplicable);
    }
}
