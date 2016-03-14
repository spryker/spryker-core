<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RuleConditionTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;

class Parser
{

    const TOKENISE_REGEXP = '((\f+|\(|\)|\*|\^|/)|\s+)';

    /**
     * @var array|string
     */
    protected $comparators = [
        RuleInterface::COMPARATOR_EQUAL,
        RuleInterface::COMPARATOR_NOT_EQUAL,
        RuleInterface::COMPARATOR_BIGGER_EQUAL,
        RuleInterface::COMPARATOR_LESS_EQUAL,
        RuleInterface::COMPARATOR_SMALLER,
        RuleInterface::COMPARATOR_BIGGER
    ];

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\RuleRegistry
     */
    protected $ruleRegistry;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\RuleRegistry $ruleRegistry
     */
    public function __construct(RuleRegistry $ruleRegistry)
    {
        $this->ruleRegistry = $ruleRegistry;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $queryString
     *
     * @return boolean
     */
    public function parse(QuoteTransfer $quoteTransfer, $queryString)
    {
        $tokens = $this->tokeniseQueryString($queryString);

        $evalExpresion = $this->parseTokens($quoteTransfer, $tokens);

        return $this->evaluateExpression($evalExpresion);

    }

    /**
     * @param string $queryString
     * @return array|\string[]
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     */
    protected function tokeniseQueryString($queryString)
    {
        if (empty($queryString)) {
            throw new QueryStringException('Empty query string provided');
        }

        $tokens = preg_split(
            self::TOKENISE_REGEXP,
            $queryString,
            null,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );

        $tokens = array_map('trim', $tokens);

        return $tokens;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $tokens
     * @return string
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     */
    public function parseTokens(QuoteTransfer $quoteTransfer, array $tokens)
    {
        $evalExpression = '';
        $ruleName = '';
        $comparator = '';
        $parenthesisDepth = 0;
        $lastToken = '';

        foreach ($tokens as $token) {
            switch ($token) {
                case '(':
                    $evalExpression .= '(';
                    $parenthesisDepth++;
                    break;
                case ')':
                    $evalExpression .= ')';
                    $parenthesisDepth--;
                    break;
                case 'and':
                    if ($lastToken == 'and') {
                        throw new QueryStringException('Invalid expresion!');
                    }
                    $evalExpression .= ' && ';
                    break;
                case 'or':
                    if ($lastToken == 'or') {
                        throw new QueryStringException('Invalid expresion!');
                    }
                    $evalExpression .= ' || ';
                    break;
                default:
                    if (substr($token, 0, 1) === ':') {
                        if (!empty($ruleName)) {
                            throw new QueryStringException('Invalid expresion!');
                        }
                        $ruleName = $token;
                        break;
                    }

                    if (empty($ruleName)) {
                        throw new QueryStringException('Invalid expresion!');
                    }

                    if (in_array($token, $this->comparators)) {
                        $comparator = $token;
                        break;
                    }

                    if (empty($comparator)) {
                        throw new QueryStringException('Rule comparator not found!');
                    }

                    $ruleName = $this->cleanRuleName($ruleName);
                    $isRuleSatisfied = $this->evaluateRule($quoteTransfer, $ruleName, $comparator, $token);

                    $evalExpression .= $isRuleSatisfied ? ' true ' : ' false ';

                    $ruleName = '';
                    $comparator = '';
                    break;
            }

            $lastToken = $token;
        }

        if ($parenthesisDepth != 0) {
            throw new QueryStringException('Parenthesis not matching!');
        }

        return $evalExpression;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer;
     * @param string $ruleName
     * @param string $comparator
     * @param string $value
     *
     * @return bool
     */
    protected function evaluateRule(QuoteTransfer $quoteTransfer, $ruleName, $comparator, $value)
    {
        $rule = $this->ruleRegistry->getByName($ruleName);

        if ($rule === null) {
            return false;
        }

        $ruleConditionTransfer = new RuleConditionTransfer();
        $ruleConditionTransfer->setQuote($quoteTransfer);
        $ruleConditionTransfer->setComparator($comparator);
        $ruleConditionTransfer->setInputValue($value);

        return (bool)$rule->isSatisfiedBy($ruleConditionTransfer);
    }

    /**
     * @param string $expression
     *
     * @return bool
     */
    protected function evaluateExpression($expression)
    {
        if (empty($expression)) {
            return false;
        }

        $evaluationResult = false;
        eval(sprintf('$evaluationResult = %s;', $expression));

        return $evaluationResult;
    }

    /**
     * @param string $ruleName
     *
     * @return string
     */
    protected function cleanRuleName($ruleName)
    {
        return str_replace(':', '', strtolower($ruleName));
    }

}
