<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Application\Log\Processor;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Application\Log\Processor\EnvironmentProcessor;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group EnvironmentProcessorTest
 */
class EnvironmentProcessorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInvokeShouldAddEnvironmentInformationToRecordsExtra()
    {
        $processor = new EnvironmentProcessor();
        $record = ['extra'];
        $result = $processor($record);

        $this->assertArrayHasKey(EnvironmentProcessor::EXTRA, $result['extra']);
    }

}
