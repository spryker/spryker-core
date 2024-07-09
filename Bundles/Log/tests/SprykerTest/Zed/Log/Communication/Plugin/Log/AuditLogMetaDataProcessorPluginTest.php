<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Log\Communication\Plugin\Log;

use Codeception\Test\Unit;
use Spryker\Zed\Log\Communication\Plugin\Log\AuditLogMetaDataProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Log
 * @group Communication
 * @group Plugin
 * @group Log
 * @group AuditLogMetaDataProcessorPluginTest
 * Add your own group annotations below this line
 */
class AuditLogMetaDataProcessorPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testInvokeAddsLogType(): void
    {
        // Arrange
        $auditLogMetaDataProcessorPlugin = new AuditLogMetaDataProcessorPlugin();

        // Act
        $result = $auditLogMetaDataProcessorPlugin->__invoke(['extra' => []]);

        // Assert
        $this->assertSame('audit_log', $result['extra']['log_type']);
    }
}
