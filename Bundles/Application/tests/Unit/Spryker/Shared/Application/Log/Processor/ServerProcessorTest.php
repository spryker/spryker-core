<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Application\Log\Processor;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Application\Log\Processor\ServerProcessor;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group ServerProcessorTest
 */
class ServerProcessorTest extends PHPUnit_Framework_TestCase
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
