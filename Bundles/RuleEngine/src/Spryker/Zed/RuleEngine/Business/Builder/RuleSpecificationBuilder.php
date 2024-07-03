<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Builder;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorChecker;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface;
use Spryker\Zed\RuleEngine\Business\Exception\QueryStringException;
use Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface;
use Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface;
use Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface;
use Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

class RuleSpecificationBuilder implements RuleSpecificationBuilderInterface
{
    /**
     * @var string
     */
    public const OPEN_PARENTHESIS = '(';

    /**
     * @var string
     */
    public const CLOSE_PARENTHESIS = ')';

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface
     */
    protected TokenizerInterface $tokenizer;

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface
     */
    protected RuleSpecificationProviderResolverInterface $ruleSpecificationProviderResolver;

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface
     */
    protected ComparatorCheckerInterface $comparatorChecker;

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface
     */
    protected ClauseValidatorInterface $clauseValidator;

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface
     */
    protected MetaDataProviderInterface $metaDataProvider;

    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface
     */
    protected RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin;

    /**
     * @var list<string>
     */
    protected array $compoundComparatorExpressions = [];

    /**
     * @param \Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface $tokenizer
     * @param \Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface $ruleSpecificationProviderResolver
     * @param \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface $comparatorChecker
     * @param \Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface $clauseValidator
     * @param \Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface $metaDataProvider
     */
    public function __construct(
        TokenizerInterface $tokenizer,
        RuleSpecificationProviderResolverInterface $ruleSpecificationProviderResolver,
        ComparatorCheckerInterface $comparatorChecker,
        ClauseValidatorInterface $clauseValidator,
        MetaDataProviderInterface $metaDataProvider
    ) {
        $this->tokenizer = $tokenizer;
        $this->ruleSpecificationProviderResolver = $ruleSpecificationProviderResolver;
        $this->comparatorChecker = $comparatorChecker;
        $this->clauseValidator = $clauseValidator;
        $this->metaDataProvider = $metaDataProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    public function build(RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer): RuleSpecificationInterface
    {
        $this->ruleSpecificationProviderPlugin = $this->ruleSpecificationProviderResolver->resolveRuleSpecificationProviderPlugin(
            $ruleEngineSpecificationRequestTransfer->getRuleEngineSpecificationProviderRequestOrFail(),
        );

        $tokens = $this->tokenizer->tokenizeQueryString($ruleEngineSpecificationRequestTransfer->getQueryStringOrFail());

        return $this->buildTree($tokens);
    }

    /**
     * @param list<string> $tokens
     * @param int $currentTokenIndex
     * @param int $parenthesisDepth
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\QueryStringException
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    protected function buildTree(array $tokens, int &$currentTokenIndex = 0, int &$parenthesisDepth = 0): RuleSpecificationInterface
    {
        $leftNode = null;
        $compositeNode = null;
        $lastLogicalComparator = null;
        $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();

        $tokensCount = count($tokens);
        while ($tokensCount > $currentTokenIndex) {
            $token = strtolower($tokens[$currentTokenIndex]);

            switch (true) {
                case $token === static::OPEN_PARENTHESIS:
                    $parenthesisDepth++;
                    $currentTokenIndex++;
                    $childTree = $this->buildTree($tokens, $currentTokenIndex, $parenthesisDepth);

                    if ($leftNode === null) {
                        $leftNode = $childTree;

                        break;
                    }

                    $compositeNode = $this->createCompositeNode($leftNode, $childTree, $compositeNode, $lastLogicalComparator);

                    break;
                case $token === static::CLOSE_PARENTHESIS:
                    $parenthesisDepth--;

                    if ($compositeNode == null) {
                        /** @phpstan-var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode */
                        return $leftNode;
                    }

                    return $compositeNode;
                case $this->isLogicalComparator($token):
                    $lastLogicalComparator = $token;

                    break;
                case $this->isField($token):
                    $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();
                    $ruleEngineClauseTransfer = $this->setClauseField($token, $ruleEngineClauseTransfer);

                    break;
                case $this->isComparator($token):
                    if ($ruleEngineClauseTransfer->getOperator()) {
                        $token = $ruleEngineClauseTransfer->getOperatorOrFail() . ' ' . $token;
                    }

                    $ruleEngineClauseTransfer->setOperator($token);

                    break;
                case $this->isValue($token):
                    $value = $this->clearQuotes($token);
                    $ruleEngineClauseTransfer->setValue($value);

                    $this->clauseValidator->validateClause($ruleEngineClauseTransfer, $this->ruleSpecificationProviderPlugin);

                    if ($leftNode === null) {
                        $leftNode = $this->ruleSpecificationProviderPlugin->getRuleSpecificationContext($ruleEngineClauseTransfer);

                        break;
                    }

                    $rightNode = $this->ruleSpecificationProviderPlugin->getRuleSpecificationContext($ruleEngineClauseTransfer);
                    $compositeNode = $this->createCompositeNode($leftNode, $rightNode, $compositeNode, $lastLogicalComparator);

                    break;
                default:
                    throw new QueryStringException(
                        sprintf("Token '%s' could not be identified by specification builder.", $token),
                    );
            }

            $currentTokenIndex++;
        }

        if ($parenthesisDepth !== 0) {
            throw new QueryStringException('Parenthesis not matching.');
        }

        return $compositeNode ?? $leftNode ?? throw new QueryStringException('Failed to build specification from query string.');
    }

    /**
     * @return list<string>
     */
    protected function getCompoundComparatorExpressions(): array
    {
        if ($this->compoundComparatorExpressions === []) {
            $this->compoundComparatorExpressions = $this->comparatorChecker->getCompoundComparatorExpressions();
        }

        return $this->compoundComparatorExpressions;
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface|null $compositeNode
     * @param string|null $logicalComparator
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface|null
     */
    protected function createCompositeNode(
        RuleSpecificationInterface $leftNode,
        RuleSpecificationInterface $rightNode,
        ?RuleSpecificationInterface $compositeNode,
        ?string $logicalComparator
    ): ?RuleSpecificationInterface {
        if ($compositeNode !== null) {
            $leftNode = $compositeNode;
        }

        if ($logicalComparator === ComparatorChecker::LOGICAL_COMPARATOR_AND) {
            return $this->ruleSpecificationProviderPlugin->createAnd($leftNode, $rightNode);
        }

        if ($logicalComparator === ComparatorChecker::LOGICAL_COMPARATOR_OR) {
            return $this->ruleSpecificationProviderPlugin->createOr($leftNode, $rightNode);
        }

        return $compositeNode;
    }

    /**
     * @param string $fieldName
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineClauseTransfer
     */
    protected function setClauseField(string $fieldName, RuleEngineClauseTransfer $ruleEngineClauseTransfer): RuleEngineClauseTransfer
    {
        if (strpos($fieldName, '.') !== false) {
            [$fieldName, $attribute] = explode('.', $fieldName);
            $ruleEngineClauseTransfer->setAttribute($attribute);
        }

        return $ruleEngineClauseTransfer->setField(trim($fieldName));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function clearQuotes(string $value): string
    {
        return str_replace(['"', '\''], '', $value);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    protected function isField(string $token): bool
    {
        return $this->metaDataProvider->isFieldAvailable($this->ruleSpecificationProviderPlugin->getRulePlugins(), $token);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    protected function isLogicalComparator(string $token): bool
    {
        return $this->comparatorChecker->isLogicalComparator($token);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    protected function isComparator(string $token): bool
    {
        if (in_array($token, $this->getCompoundComparatorExpressions(), true)) {
            return true;
        }

        $ruleEngineClauseTransfer = new RuleEngineClauseTransfer();
        $ruleEngineClauseTransfer->setOperator($token);

        return $this->comparatorChecker->isExistingComparator($ruleEngineClauseTransfer);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    protected function isValue(string $token): bool
    {
        $firstSymbol = substr($token, 0, 1);
        $lastSymbol = substr($token, -1);

        if (($firstSymbol === '"' && $lastSymbol === '"') || ($firstSymbol === "'" && $lastSymbol === "'")) {
            return true;
        }

        return false;
    }
}
