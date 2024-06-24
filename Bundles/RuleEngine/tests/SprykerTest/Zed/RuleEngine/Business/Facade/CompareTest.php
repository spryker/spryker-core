<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RuleEngineClauseBuilder;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use SprykerTest\Zed\RuleEngine\RuleEngineBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RuleEngine
 * @group Business
 * @group Facade
 * @group CompareTest
 * Add your own group annotations below this line
 */
class CompareTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\RuleEngine\RuleEngineBusinessTester
     */
    protected RuleEngineBusinessTester $tester;

    /**
     * @dataProvider shouldReturnCorrectResultForgivenClauseAndValueDataProvider
     *
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param mixed $value
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testShouldReturnCorrectResultForgivenClauseAndValue(
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        mixed $value,
        bool $expectedResult
    ): void {
        // Act
        $result = $this->tester->getFacade()->compare($ruleEngineClauseTransfer, $value);

        // Assert
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function shouldReturnCorrectResultForgivenClauseAndValueDataProvider(): array
    {
        return [
            'match all with not empty value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => '*',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'value',
                true,
            ],
            'match all with empty value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => '*',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                '',
                false,
            ],
            'equal clause with equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => 'value',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'value',
                true,
            ],
            'equal clause with not equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '=',
                    RuleEngineClauseTransfer::VALUE => 'value',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'another value',
                false,
            ],
            'not equal clause with equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '!=',
                    RuleEngineClauseTransfer::VALUE => 'value',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'value',
                false,
            ],
            'not equal clause with not equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '!=',
                    RuleEngineClauseTransfer::VALUE => 'value',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'another value',
                true,
            ],
            'greater clause with greater value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '>',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '2',
                true,
            ],
            'greater clause with smaller value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '>',
                    RuleEngineClauseTransfer::VALUE => '2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                false,
            ],
            'greater clause with equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '>',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                false,
            ],
            'greater or equal clause with greater value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '>=',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '2',
                true,
            ],
            'greater or equal clause with equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '>=',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                true,
            ],
            'greater or equal clause with smaller value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '>=',
                    RuleEngineClauseTransfer::VALUE => '2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                false,
            ],
            'less clause with greater value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '<',
                    RuleEngineClauseTransfer::VALUE => '2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                true,
            ],
            'less clause with smaller value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '<',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '2',
                false,
            ],
            'less clause with equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '<',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                false,
            ],
            'less or equal clause with greater value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '<=',
                    RuleEngineClauseTransfer::VALUE => '2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                true,
            ],
            'less or equal clause with equal value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '<=',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '1',
                true,
            ],
            'less or equal clause with smaller value' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => '<=',
                    RuleEngineClauseTransfer::VALUE => '1',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['number'],
                ]))->build(),
                '2',
                false,
            ],
            'is in clause with value in list' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => 'is in',
                    RuleEngineClauseTransfer::VALUE => '1;2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list'],
                ]))->build(),
                '2',
                true,
            ],
            'is in clause with value not in list' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => 'is in',
                    RuleEngineClauseTransfer::VALUE => '1;2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list'],
                ]))->build(),
                '3',
                false,
            ],
            'is not in clause with value in list' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => 'is not in',
                    RuleEngineClauseTransfer::VALUE => '1;2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list'],
                ]))->build(),
                '2',
                false,
            ],
            'is not in clause with value not in list' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => 'is not in',
                    RuleEngineClauseTransfer::VALUE => '1;2',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['list'],
                ]))->build(),
                '3',
                true,
            ],
            'contains clause with value containing substring' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => 'contains',
                    RuleEngineClauseTransfer::VALUE => 'substring',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'this is a substring',
                true,
            ],
            'contains clause with value not containing substring' => [
                (new RuleEngineClauseBuilder([
                    RuleEngineClauseTransfer::OPERATOR => 'contains',
                    RuleEngineClauseTransfer::VALUE => 'substring',
                    RuleEngineClauseTransfer::ACCEPTED_TYPES => ['string'],
                ]))->build(),
                'this is not a value',
                false,
            ],
        ];
    }
}
