<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor;

use Codeception\Test\Unit;
use Spryker\Shared\Application\Log\Processor\ServerProcessor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group ServerProcessorTest
 * Add your own group annotations below this line
 */
class ServerProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function testInvokeShouldAddServerInformationToRecordsExtra()
    {
        $_SERVER['SERVER_NAME'] = 'www.example.com';
        $processor = new ServerProcessor();
        $record = ['extra'];
        $result = $processor($record);

        $this->assertArrayHasKey(ServerProcessor::EXTRA, $result['extra']);
    }

    /**
     * @return void
     */
    public function testInvokeWithSecuredConnectionShouldAddServerInformationToRecordsExtra()
    {
        $_SERVER['HTTPS'] = 'on';

        $processor = new ServerProcessor();
        $record = ['extra'];
        $result = $processor($record);

        $this->assertArrayHasKey(ServerProcessor::EXTRA, $result['extra']);
    }
}
