<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Log\Plugin\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuditLoggerConfigCriteriaTransfer;
use Spryker\Glue\Log\Plugin\Log\GlueSecurityAuditLoggerConfigPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Log
 * @group Plugin
 * @group Log
 * @group GlueSecurityAuditLoggerConfigPluginTest
 * Add your own group annotations below this line
 */
class GlueSecurityAuditLoggerConfigPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForSecurityChannel(): void
    {
        // Arrange
        $glueSecurityAuditLoggerConfigPlugin = new GlueSecurityAuditLoggerConfigPlugin();

        // Act
        $isApplicable = $glueSecurityAuditLoggerConfigPlugin->isApplicable(
            (new AuditLoggerConfigCriteriaTransfer())->setChannelName('security'),
        );

        // Assert
        $this->assertTrue($isApplicable);
    }
}
