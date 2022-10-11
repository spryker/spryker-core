<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Business\MessageDataFilter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer;
use Generated\Shared\Transfer\MessageDataFilterItemConfigurationTransfer;
use Generated\Shared\Transfer\TestMessageWithDataFilterConfigurationTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBrokerAws
 * @group Business
 * @group MessageDataFilter
 * @group StripNullFieldsMessageDataFilterPluginTest
 * Add your own group annotations below this line
 * @group MessageDataFilter
 */
class StripNullFieldsMessageDataFilterPluginTest extends Unit
{
    protected MessageBrokerAwsBusinessTester $tester;

    /**
     * @dataProvider stripNullFieldsDataProvider
     *
     * @param array<mixed> $data
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param array<mixed> $expectedOutput
     *
     * @return void
     */
    public function testFilter(array $data, AbstractTransfer $transfer, array $expectedOutput): void
    {
        $filter = $this->tester->getFactory()->createStripNullFieldsMessageDataFilter();

        $this->assertSame($expectedOutput, $filter->filter($data, $transfer));
    }

    /**
     * @return array<mixed>
     */
    public function stripNullFieldsDataProvider(): array
    {
        $baseData = [
            'foo' => 'bar',
            'bar' => null,
            'nested' => [
                'foo' => null,
                'bar' => 'baz',
            ],
        ];

        $defaultExpectedOutput = [
            'foo' => 'bar',
            'nested' => [
                'bar' => 'baz',
            ],
        ];

        return [
            'null value fields are removed' => [
                $baseData,
                new TestMessageWithDataFilterConfigurationTransfer(),
                $defaultExpectedOutput,
            ],
            'configuration on transfer can disable filtering' => [
                $baseData,
                (new TestMessageWithDataFilterConfigurationTransfer())
                    ->setDataFilterConfiguration(
                        (new MessageDataFilterConfigurationTransfer())
                            ->setStripNullFieldsConfiguration(
                                (new MessageDataFilterItemConfigurationTransfer())
                                    ->setDisabled(true),
                            ),
                    ),
                $baseData,
            ],

            'filter-specific configuration is optional' => [
                $baseData,
                (new TestMessageWithDataFilterConfigurationTransfer())
                    ->setDataFilterConfiguration(
                        (new MessageDataFilterConfigurationTransfer()),
                    ),
                $defaultExpectedOutput,
            ],
        ];
    }
}
