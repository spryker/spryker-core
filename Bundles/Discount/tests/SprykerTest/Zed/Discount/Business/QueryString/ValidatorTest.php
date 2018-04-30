<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\Exception\QueryBuilderException;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Validator;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group ValidatorTest
 * Add your own group annotations below this line
 */
class ValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateDecisionRuleWhenThereIsNoErrorShouldNotThrowException()
    {
        $decisionRuleMock = $this->createSpecificationBuilderMock();
        $decisionRuleMock
            ->expects($this->once())
            ->method('buildFromQueryString');

        $validator = $this->createValidator($decisionRuleMock);

        $messages = $validator->validateByType(MetaProviderFactory::TYPE_DECISION_RULE, 'query string');

        $this->assertCount(0, $messages);
    }

    /**
     * @return void
     */
    public function testValidateCollectorWhenThereIsNoErrorShouldNotThrowException()
    {
        $collectorBuilderMock = $this->createSpecificationBuilderMock();
        $collectorBuilderMock
            ->expects($this->once())
            ->method('buildFromQueryString');

        $validator = $this->createValidator(null, $collectorBuilderMock);

        $messages = $validator->validateByType(MetaProviderFactory::TYPE_COLLECTOR, 'query string');

        $this->assertCount(0, $messages);
    }

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

        $messages = $validator->validateByType(MetaProviderFactory::TYPE_DECISION_RULE, 'query string');

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

        $messages = $validator->validateByType(MetaProviderFactory::TYPE_COLLECTOR, 'query string');

        $this->assertCount(1, $messages);
        $this->assertEquals($queryStringException, $messages[0]);
    }

    /**
     * @return void
     */
    public function testValidateCollectorWhenComparatorExceptionThrownShouldStoreIntoResponseArray()
    {
        $queryStringException = 'Test';

        $collectorMock = $this->createSpecificationBuilderMock();
        $collectorMock
            ->expects($this->once())
            ->method('buildFromQueryString')
            ->willThrowException(
                new ComparatorException($queryStringException)
            );

        $validator = $this->createValidator(null, $collectorMock);

        $messages = $validator->validateByType(MetaProviderFactory::TYPE_COLLECTOR, 'query string');

        $this->assertCount(1, $messages);
        $this->assertEquals($queryStringException, $messages[0]);
    }

    /**
     * @return void
     */
    public function testValidateCollectorShouldThrowExceptionWhenNonExistingTypeUsed()
    {
        $this->expectException(QueryBuilderException::class);

        $validator = $this->createValidator();

        $validator->validateByType('type', 'query string');
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder|null $decisionRuleMock
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder|null $collectorMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Validator
     */
    protected function createValidator(
        ?SpecificationBuilder $decisionRuleMock = null,
        ?SpecificationBuilder $collectorMock = null
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createSpecificationBuilderMock()
    {
        return $this->getMockBuilder(SpecificationBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock()
    {
        return $this->getMockBuilder(DecisionRuleSpecificationInterface::class)->getMock();
    }
}
