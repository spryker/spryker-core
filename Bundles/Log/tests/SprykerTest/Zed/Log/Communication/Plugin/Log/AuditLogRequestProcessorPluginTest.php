<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Log\Communication\Plugin\Log;

use Codeception\Test\Unit;
use Spryker\Zed\Log\Communication\Plugin\Log\AuditLogRequestProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Log
 * @group Communication
 * @group Plugin
 * @group Log
 * @group AuditLogRequestProcessorPluginTest
 * Add your own group annotations below this line
 */
class AuditLogRequestProcessorPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testInvokeAddsRequestData(): void
    {
        // Arrange
        $auditLogRequestProcessorPlugin = new AuditLogRequestProcessorPlugin();

        // Act
        $result = $auditLogRequestProcessorPlugin->__invoke(['extra' => [], 'context' => []]);

        // Assert
        $this->assertArrayHasKey('extra', $result);
        $this->assertArrayHasKey('request', $result['extra']);
        $this->assertArrayHasKey('requestId', $result['extra']['request']);
        $this->assertArrayHasKey('type', $result['extra']['request']);
        $this->assertArrayHasKey('request_params', $result['extra']['request']);
    }
}
