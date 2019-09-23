<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor;

use Codeception\Test\Unit;
use Spryker\Shared\Application\Log\Processor\ResponseProcessor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group ResponseProcessorTest
 * Add your own group annotations below this line
 */
class ResponseProcessorTest extends Unit
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
