<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString;

use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Validator;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleBuilder;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\AbstractSpecificationBuilder;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testValidateDecisionRuleShouldCaptureExceptionsThrownIntoResponseArray()
    {
        $queryStringException = 'Test';

        $decisionRuleMock = $this->createSpecificationBuilderMock();
        $decisionRuleMock
            ->expects($this->once())
            ->method('buildFromQueryString')
            ->willThrowException(
                new QueryStringException($queryStringException)
            );

        $validator = $this->createValidator($decisionRuleMock);

        $messages = $validator->validateByType(SpecificationBuilder::TYPE_DECISION_RULE, 'query string');

        $this->assertCount(1, $messages);
        $this->assertEquals($queryStringException, $messages[0]);
    }

    /**
     * @return void
     */
    public function testValidateCollectorShouldCaptureExceptionsThrownIntoResponseArray()
    {
        $queryStringException = 'Test';

        $collectorMock = $this->createSpecificationBuilderMock();
        $collectorMock
            ->expects($this->once())
            ->method('buildFromQueryString')
            ->willThrowException(
                new QueryStringException($queryStringException)
            );

        $validator = $this->createValidator(null, $collectorMock);

        $messages = $validator->validateByType(SpecificationBuilder::TYPE_COLLECTOR, 'query string');

        $this->assertCount(1, $messages);
        $this->assertEquals($queryStringException, $messages[0]);
    }

    /**
     * @return void
     */
    public function testValidateCollectorShouldThrowExceptionWhenNonExistingTypeUsed()
    {
        $this->expectException(\InvalidArgumentException::class);

        $validator = $this->createValidator();

        $validator->validateByType('type', 'query string');

    }

    /**
     * @param SpecificationBuilder|null $decisionRuleMock
     * @param SpecificationBuilder|null $collectorMock
     *
     * @return Validator
     */
    protected function createValidator(
        SpecificationBuilder $decisionRuleMock = null,
        SpecificationBuilder $collectorMock = null
    ) {

        if ($decisionRuleMock === null) {
            $decisionRuleMock = $this->createSpecificationBuilderMock();
        }

        if ($collectorMock === null) {
            $collectorMock = $this->createSpecificationBuilderMock();
        }

        return new Validator(
            $decisionRuleMock,
            $collectorMock
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SpecificationBuilder
     */
    protected function createSpecificationBuilderMock()
    {
        return $this->getMockBuilder(SpecificationBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock()
    {
       return $this->getMock(DecisionRuleSpecificationInterface::class);
    }
}
