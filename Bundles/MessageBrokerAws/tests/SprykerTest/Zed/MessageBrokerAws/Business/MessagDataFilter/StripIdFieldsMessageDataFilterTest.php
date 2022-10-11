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
 * @group StripIdFieldsMessageDataFilterTest
 * Add your own group annotations below this line
 * @group MessageDataFilter
 */
class StripIdFieldsMessageDataFilterTest extends Unit
{
    protected MessageBrokerAwsBusinessTester $tester;

    /**
     * @dataProvider stripIdFieldsDataProvider
     *
     * @param array<mixed> $data
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     * @param array<mixed> $expectedOutput
     *
     * @return void
     */
    public function testFilter(array $data, AbstractTransfer $transfer, array $expectedOutput): void
    {
        $filter = $this->tester->getFactory()->createStripIdFieldsMessageDataFilter();

        $this->assertSame($expectedOutput, $filter->filter($data, $transfer));
    }

    /**
     * @return array<mixed>
     */
    public function stripIdFieldsDataProvider(): array
    {
        $baseData = [
            'idSomething' => 1,
            'fkOtherThing' => 2,
            'notAnId' => 3,
            'nested' => [
                'idFoo' => 1,
                'fkBar' => 2,
                'idnotvalid' => 3,
            ],
        ];

        $defaultExpectedOutput = [
            'notAnId' => 3,
            'nested' => [
                'idnotvalid' => 3,
            ],
        ];

        return [
            'removes fields with id and fk prefix by default' => [
                $baseData,
                new TestMessageWithDataFilterConfigurationTransfer(),
                $defaultExpectedOutput,
            ],

            'configuration from transfer can disable filter' => [
                $baseData,
                (new TestMessageWithDataFilterConfigurationTransfer())
                    ->setDataFilterConfiguration(
                        (new MessageDataFilterConfigurationTransfer())
                            ->setStripIdFieldsConfiguration(
                                (new MessageDataFilterItemConfigurationTransfer())
                                    ->setDisabled(true),
                            ),
                    ),
                $baseData,
            ],

            'configuration from transfer can change patterns' => [
                $baseData,
                (new TestMessageWithDataFilterConfigurationTransfer())
                    ->setDataFilterConfiguration(
                        (new MessageDataFilterConfigurationTransfer())
                            ->setStripIdFieldsConfiguration(
                                (new MessageDataFilterItemConfigurationTransfer())
                                    ->setpatterns(['/^id/']),
                            ),
                    ),
                [
                    'fkOtherThing' => 2,
                    'notAnId' => 3,
                    'nested' => [
                        'fkBar' => 2,
                    ],
                ],
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
