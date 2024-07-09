<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Log\Plugin\Log;

use Codeception\Test\Unit;
use Spryker\Glue\Log\Plugin\Log\AuditLogRequestProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Log
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
