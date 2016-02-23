<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RuleConditionTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Parser;
use Spryker\Zed\Discount\Business\QueryString\RuleInterface;
use Spryker\Zed\Discount\Business\QueryString\RuleRegistry;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWhenSingleConditionEvaluatesToTrueShouldReturnTrue()
    {
        $input = ':subTotal = 12.12';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingNotEqualsAndConditionReturnsFalseShouldReturnTrue()
    {
        $input = ':subTotal != 12.12';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() != 12.11;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingNotEqualsAndConditionReturnsTrueShouldReturnFalse()
    {
        $input = ':subTotal != 12.12';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() != 12.12;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testWhenSingleConditionEvaluatesToFalseShouldReturnFalse()
    {
        $input = ':subTotal = 12.12';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.11;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingAndConditionBothEvaluatesToTrueShouldReturnTrue()
    {
        $input = ':subTotal = 12.12 and :grossPrice = 150';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
            'grossPrice' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 150;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingORConditionOneEvaluatesToTrueShouldReturnFalse()
    {
        $input = ':subTotal = 12.12 and :grossPrice = 150';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
            'grossPrice' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 120;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingORConditionOneEvaluatesToTrueShouldReturnTrue()
    {
        $input = ':subTotal = 12.12 or :grossPrice = 150';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
            'grossPrice' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 120;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenConditionsUsedOneFailingShouldReturnFalse()
    {
        $input = ':subTotal = 12.12 and :sku != sku-123';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
            'sku' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() != 'sku-123';
            })
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingMoreConditionShouldReturnTrueForAllBigerThan()
    {
        $input = ':subTotal > 6';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return 7 > $ruleConditionTransfer->getInputValue();
            })
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingMoreEqualConditionShouldReturnTrueForAllBigerAndEqualThan()
    {
        $input = ':subTotal >= 5';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 5;
            })
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingLessConditionShouldReturnTrueForAllLowerThan()
    {
        $input = ':subTotal < 6';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return 5 < $ruleConditionTransfer->getInputValue();
            })
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenParsingInvalidQueryStringShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = 'subTotal= 12.12 andsku != sku-123';

        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenUsingNonExistantRuleShouldEvaluateThatRuleToFalse()
    {
        $input = ':subTotal = 12.12 and :sku = sku-123';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testWhenUsingParenthesisShouldEvaluateToBasedOnParenthesisMatches()
    {
        $input = '(:subTotal = 12.12 and :sku = sku-123) or :quantity >= 100';

        $ruleMocks = [
            'subtotal' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 12.12;
            }),
            'sku' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 'sku-other';
            }),
            'quantity' => $this->createRuleMockWithExpectedSatisfiedBy(function (RuleConditionTransfer $ruleConditionTransfer) {
                return $ruleConditionTransfer->getInputValue() == 100;
            }),
        ];

        $result = $this->parserWithProvidedRulesAndQueryString($ruleMocks, $input);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWhenParenthesisNumberIsInvalidShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '(:subTotal = 12.12 and :sku = sku-123)( or :quantity >= 100';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }


    /**
     * @return void
     */
    public function testWhenRuleValueIsNotProviderShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '(:subTotal = 12.12 and :sku = ) or :quantity >= 100';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenRuleComparatorIsNotProviderShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '(:subTotal = 12.12 and :sku 1 ) or :quantity >= 100';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenRuleNameIsNotProviderShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '( = 12.12 and :sku =  1) or :quantity >= 100';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenMultipleANDConditionsInRowUsedShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '(:subTotal = 12.12 and and :sku = 1) or :quantity >= 100';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenMultipleORConditionsInRowUsedShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '(:subTotal = 12.12 or or :sku = 1) or :quantity >= 100';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenEmptyQueryStringProvidedShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = '';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @return void
     */
    public function testWhenInvalidQueryStringProvidedShouldThrowException()
    {
        $this->setExpectedException(QueryStringException::class);

        $input = 'lwkj and sfo3498  or (rhkj lask) jdk10 aksd1 alksd';
        $this->parserWithProvidedRulesAndQueryString([], $input);
    }

    /**
     * @param array|\Spryker\Zed\Discount\Business\QueryString\RuleInterface[] $ruleMocks
     * @return \Spryker\Zed\Discount\Business\QueryString\Parser
     */
    protected function createParser(array $ruleMocks)
    {
        return new Parser($this->createRuleRegistry($ruleMocks));
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function creatQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     *
     * @param array|\Spryker\Zed\Discount\Business\QueryString\RuleInterface[] $ruleMocks
     * @return \Spryker\Zed\Discount\Business\QueryString\RuleRegistry
     */
    protected function createRuleRegistry(array $ruleMocks)
    {
        return new RuleRegistry($ruleMocks);
    }

    /**
     * @param callable $expected
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\RuleInterface
     */
    protected function createRuleMockWithExpectedSatisfiedBy(callable $expected)
    {
        $ruleMock = $this->getMock(RuleInterface::class);

        $ruleMock->expects($this->once())
          ->method('isSatisfiedBy')
          ->willReturnCallback($expected);

        return $ruleMock;

    }

    /**
     * @param array|\Spryker\Zed\Discount\Business\QueryString\RuleInterface[] $ruleMocks
     * @param string $input
     *
     * @return bool
     */
    protected function parserWithProvidedRulesAndQueryString(array $ruleMocks, $input)
    {
        $parser = $this->createParser($ruleMocks);
        $quoteTransfer = $this->creatQuoteTransfer();
        $result = $parser->parse($quoteTransfer, $input);

        return $result;
    }
}
