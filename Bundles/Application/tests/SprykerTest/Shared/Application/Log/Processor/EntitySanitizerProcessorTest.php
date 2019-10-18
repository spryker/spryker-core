<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor;

use Codeception\Test\Unit;
use Spryker\Shared\Application\Log\Processor\EntitySanitizerProcessor;
use Spryker\Shared\Log\Sanitizer\Sanitizer;
use SprykerTest\Shared\Application\Log\Processor\Fixtures\Entity;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group EntitySanitizerProcessorTest
 * Add your own group annotations below this line
 */
class EntitySanitizerProcessorTest extends Unit
{
    /**
     * @dataProvider getContext
     *
     * @param array $context
     *
     * @return void
     */
    public function testInvokeShouldAddSanitizedEntityDataToRecordsExtra(array $context)
    {
        $record = ['message' => 'message', 'context' => $context];
        $filterFields = [
            'first_name',
        ];
        $sanitizer = new Sanitizer($filterFields, '***');
        $processor = new EntitySanitizerProcessor($sanitizer);
        $result = $processor($record);

        $this->assertArrayHasKey(EntitySanitizerProcessor::EXTRA, $result['extra']);
    }

    /**
     * @return array
     */
    public function getContext()
    {
        $entity = new Entity();

        return [
            [[$entity]],
            [['entity' => $entity]],
        ];
    }

    /**
     * @return void
     */
    public function testIfContextDoesNotContainEntityDoNothing()
    {
        $record = ['message' => 'message', 'context' => ''];
        $sanitizer = new Sanitizer([], '***');
        $processor = new EntitySanitizerProcessor($sanitizer);
        $result = $processor($record);

        $this->assertSame($record, $result);
    }
}
