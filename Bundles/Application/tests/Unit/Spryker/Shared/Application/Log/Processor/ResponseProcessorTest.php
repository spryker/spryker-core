<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Application\Log\Processor;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Application\Log\Processor\ResponseProcessor;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group ResponseProcessorTest
 */
class ResponseProcessorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWHenContextContainsResponseResponseShouldBeRemovedFromContext()
    {
        $processor = new ResponseProcessor();
        $record = ['extra' => [], ResponseProcessor::RECORD_CONTEXT => [ResponseProcessor::EXTRA => 'response']];
        $result = $processor($record);

        $this->assertArrayNotHasKey(ResponseProcessor::EXTRA, $result[ResponseProcessor::RECORD_CONTEXT]);
    }

}
