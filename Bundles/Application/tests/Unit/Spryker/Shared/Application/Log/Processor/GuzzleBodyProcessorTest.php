<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Application\Log\Processor;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Application\Log\Processor\GuzzleBodyProcessor;
use Spryker\Shared\Log\Sanitizer\Sanitizer;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group GuzzleBodyProcessorTest
 */
class GuzzleBodyProcessorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInvokeShouldAddGuzzleBodyToRecordsExtra()
    {
        $sanitizer = new Sanitizer([], '***');
        $processor = new GuzzleBodyProcessor($sanitizer);
        $record = ['extra' => [], 'context' => [GuzzleBodyProcessor::EXTRA => 'guzzle body string']];
        $result = $processor($record);

        $this->assertArrayHasKey(GuzzleBodyProcessor::EXTRA, $result['extra']);
    }

    /**
     * @return void
     */
    public function testInvokeWithoutGuzzleBodyShouldNotAddToRecordsExtra()
    {
        $sanitizer = new Sanitizer([], '***');
        $processor = new GuzzleBodyProcessor($sanitizer);
        $record = ['extra' => [], 'context' => []];
        $result = $processor($record);

        $this->assertArrayNotHasKey(GuzzleBodyProcessor::EXTRA, $result['extra']);
    }

    /**
     * @dataProvider guzzleBodies()
     *
     * @param mixed $body
     * @param string $expected
     *
     * @return void
     */
    public function testInvokeWithDifferentGuzzleBody($body, $expected)
    {
        $sanitizer = new Sanitizer(['replace'], '***');
        $processor = new GuzzleBodyProcessor($sanitizer);
        $record = ['extra' => [], 'context' => [GuzzleBodyProcessor::EXTRA => $body]];
        $result = $processor($record);

        $this->assertSame($expected, $result['extra'][GuzzleBodyProcessor::EXTRA]);
    }

    /**
     * @return array
     */
    public function guzzleBodies()
    {
        return [
            ['string body', ['transfer-response' => 'string body']],
            ['{"json":"body"}', ['json' => 'body']],
            ['{"json":"body", "replace":"password"}', ['json' => 'body', 'replace' => '***']],
        ];
    }

}
