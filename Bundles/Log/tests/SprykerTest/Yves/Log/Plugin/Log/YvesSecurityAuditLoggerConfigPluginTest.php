<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Log\Plugin\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Yves\Log\Plugin\Log\YvesSecurityAuditLoggerConfigPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Log
 * @group Plugin
 * @group Log
 * @group YvesSecurityAuditLoggerConfigPluginTest
 * Add your own group annotations below this line
 */
class YvesSecurityAuditLoggerConfigPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForSecurityChannel(): void
    {
        // Arrange
        $yvesSecurityAuditLoggerConfigPlugin = new YvesSecurityAuditLoggerConfigPlugin();

        // Act
        $isApplicable = $yvesSecurityAuditLoggerConfigPlugin->isApplicable(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName('security'),
        );

        // Assert
        $this->assertTrue($isApplicable);
    }
}
