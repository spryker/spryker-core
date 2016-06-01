<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface;

class SpecificationBuilder implements SpecificationBuilderInterface
{

    const OPEN_PARENTHESIS = '(';
    const CLOSE_PARENTHESIS = ')';

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\TokenizerInterface
     */
    protected $tokenizer;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface
     */
    protected $specificationProvider;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparatorOperators;

    /**
     * @var array|string[]
     */
    protected $compoundComparatorExpressions = [];

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface
     */
    protected $clauseValidator;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\TokenizerInterface $tokenizer
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface $specificationProvider
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorOperators
     * @param \Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface $clauseValidator
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        SpecificationProviderInterface $specificationProvider,
        ComparatorOperatorsInterface $comparatorOperators,
        ClauseValidatorInterface $clauseValidator
    ) {
        $this->tokenizer = $tokenizer;
        $this->specificationProvider = $specificationProvider;
        $this->comparatorOperators = $comparatorOperators;
        $this->clauseValidator = $clauseValidator;
    }

    /**
     * @param string $queryString
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    public function buildFromQueryString($queryString)
    {
        $tokens = $this->tokenizer->tokenizeQueryString($queryString);

        return $this->buildTree($tokens);
    }

    /**
     * @param array|string[] $tokens
     * @param int $currentTokenIndex
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    protected function buildTree(array $tokens, &$currentTokenIndex = 0)
    {
        static $parenthesisDepth = 0;

        $leftNode = null;
        $compositeSpecification = null;
        $lastConditional = null;

        $countTokens = count($tokens);

        while ($countTokens > $currentTokenIndex) {

            $token = $this->cleanToken($tokens[$currentTokenIndex]);

            switch ($token) {
                case self::OPEN_PARENTHESIS:
                    $parenthesisDepth++;
                    $currentTokenIndex++;
                    $childTree = $this->buildTree($tokens, $currentTokenIndex);

                    if ($leftNode === null) {
                        $leftNode = $childTree;
                    } else {
                        $compositeSpecification = $this->createComposite(
                            $lastConditional,
                            $leftNode,
                            $childTree,
                            $compositeSpecification
                        );
                    }
                    break;

                case self::CLOSE_PARENTHESIS:
                    $parenthesisDepth--;

                    if ($compositeSpecification == null && $leftNode !== null) {
                        return $leftNode;
                    }

                    return $compositeSpecification;

                case LogicalComparators::COMPARATOR_AND:
                    $lastConditional = $token;
                    break;

                case LogicalComparators::COMPARATOR_OR:
                    $lastConditional = $token;
                    break;

                default:
                    $clauseTransfer = $this->buildClause($tokens, $currentTokenIndex);

                    if ($leftNode === null) {
                        $leftNode = $this->specificationProvider->getSpecificationContext($clauseTransfer);
                        break;
                    }

                    $rightNode = $this->specificationProvider->getSpecificationContext($clauseTransfer);

                    $compositeSpecification = $this->createComposite(
                        $lastConditional,
                        $leftNode,
                        $rightNode,
                        $compositeSpecification
                    );
            }

            $currentTokenIndex++;
        }

        if ($parenthesisDepth !== 0) {
            throw new QueryStringException('Parenthesis not matching.');
        }

        if ($compositeSpecification == null && $leftNode !== null) {
            return $leftNode;
        }

        return $compositeSpecification;
    }

    /**
     * @param array $tokens
     * @param int $currentTokenIndex
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function buildClause($tokens, &$currentTokenIndex)
    {
        $value = '';
        $fieldName = '';
        $comparatorOperator = '';
        $compoundComparator = '';

        $countTokens = count($tokens);

        while ($countTokens > $currentTokenIndex) {

            $token = $this->cleanToken($tokens[$currentTokenIndex]);

            if (!$fieldName) {
                $fieldName = $token;
                $currentTokenIndex++;
                continue;
            }

            if (!$comparatorOperator) {

                if (in_array($token, $this->getCompoundComparatorExpressions())) {
                    $compoundComparator .= $token . ' ';
                    $currentTokenIndex++;
                    continue;
                }

                if ($compoundComparator) {
                    $comparatorOperator = $compoundComparator;
                } else {
                    $comparatorOperator = $token;
                    $currentTokenIndex++;
                    continue;
                }
            }

            if (!$value) {
                $value = $this->cleanValue($token);
            }

            $clauseTransfer =  $this->createClauseTransfer($fieldName, $comparatorOperator, $value);
            $this->clauseValidator->validateClause($clauseTransfer);

            return $clauseTransfer;
        }

        throw new QueryStringException('Could not build clause from query string.');
    }

    /**
     * @return array|string[]
     */
    protected function getCompoundComparatorExpressions()
    {
        if (!$this->compoundComparatorExpressions) {
            $this->compoundComparatorExpressions = $this->comparatorOperators->getCompoundComparatorExpressions();
        }

        return $this->compoundComparatorExpressions;
    }

    /**
     * @param string $conditional
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $rightNode
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $compositeSpecification
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    protected function createComposite(
        $conditional,
        $leftNode,
        $rightNode,
        $compositeSpecification
    ) {
        if (!$conditional) {
            return $compositeSpecification;
        }

        if ($compositeSpecification !== null) {
            $leftNode = $compositeSpecification;
        }

        if ($conditional === LogicalComparators::COMPARATOR_AND) {
            $compositeSpecification = $this->specificationProvider->createAnd($leftNode, $rightNode);
        } elseif ($conditional === LogicalComparators::COMPARATOR_OR) {
            $compositeSpecification = $this->specificationProvider->createOr($leftNode, $rightNode);
        }

        return $compositeSpecification;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function cleanToken($token)
    {
        return strtolower($token);
    }

    /**
     * @param string $fieldName
     * @param string $comparatorOperator
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransfer($fieldName, $comparatorOperator, $value)
    {
        $clauseTransfer = new ClauseTransfer();
        $this->setClauseField($fieldName, $clauseTransfer);
        $clauseTransfer->setOperator(trim($comparatorOperator));
        $clauseTransfer->setValue(trim($value));

        return $clauseTransfer;
    }

    /**
     * @param string $fieldName
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    protected function setClauseField($fieldName, ClauseTransfer $clauseTransfer)
    {
        if (strpos($fieldName, '.') !== false) {
            list($fieldName, $attribute) = explode('.', $fieldName);
            $clauseTransfer->setAttribute($attribute);
        }

        $clauseTransfer->setField(trim($fieldName));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function cleanValue($value)
    {
        return str_replace('"', '', $value);
    }

}
