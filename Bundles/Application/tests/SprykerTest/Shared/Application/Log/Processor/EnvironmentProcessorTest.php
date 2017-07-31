<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Application\Log\Processor\EnvironmentProcessor;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group EnvironmentProcessorTest
 * Add your own group annotations below this line
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
