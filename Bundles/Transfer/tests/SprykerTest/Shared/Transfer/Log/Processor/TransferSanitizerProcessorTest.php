<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Transfer\Log\Processor;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Log\Sanitizer\Sanitizer;
use Spryker\Shared\Transfer\Log\Processor\TransferSanitizerProcessor;
use SprykerTest\Shared\Transfer\Log\Processor\Fixtures\ComplexTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Transfer
 * @group Log
 * @group Processor
 * @group TransferSanitizerProcessorTest
 * Add your own group annotations below this line
 */
class TransferSanitizerProcessorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getContext
     *
     * @param array $context
     *
     * @return void
     */
    public function testInvokeShouldAddSanitizedTransferToRecordsExtra(array $context)
    {
        $record = ['message' => 'message', 'context' => $context];
        $filterFields = [
            'foo',
            'string',
        ];
        $sanitizer = new Sanitizer($filterFields, '***');
        $processor = new TransferSanitizerProcessor($sanitizer);
        $result = $processor($record);

        $this->assertArrayHasKey('transfer', $result['extra']);
    }

    /**
     * @return array
     */
    public function getContext()
    {
        $transfer = new ComplexTransfer();

        return [
            [[$transfer]],
            [['transfer' => $transfer]],
        ];
    }

    /**
     * @return void
     */
    public function testIfContextDoesNotContainTransferDoNothing()
    {
        $record = ['message' => 'message', 'context' => ''];
        $sanitizer = new Sanitizer([], '***');
        $processor = new TransferSanitizerProcessor($sanitizer);
        $result = $processor($record);

        $this->assertSame($record, $result);
    }

}
