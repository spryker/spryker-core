<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Validator;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface;
use Spryker\Zed\RuleEngine\Business\Exception\QueryStringException;
use Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface;

class ClauseValidator implements ClauseValidatorInterface
{
    /**
     * @var string
     */
    protected const REGEX_FIELD_NAME = '/^[a-z0-9\.\-]+$/i';

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface
     */
    protected ComparatorCheckerInterface $comparatorChecker;

    /**
     * @var \Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface
     */
    protected MetaDataProviderInterface $metaDataProvider;

    /**
     * @param \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface $comparatorChecker
     * @param \Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface $metaDataProvider
     */
    public function __construct(
        ComparatorCheckerInterface $comparatorChecker,
        MetaDataProviderInterface $metaDataProvider
    ) {
        $this->comparatorChecker = $comparatorChecker;
        $this->metaDataProvider = $metaDataProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
     *
     * @return void
     */
    public function validateClause(
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
    ): void {
        $this->validateComparatorOperators($ruleEngineClauseTransfer);
        $this->validateField($ruleEngineClauseTransfer, $ruleSpecificationProviderPlugin);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\QueryStringException
     *
     * @return bool
     */
    protected function validateComparatorOperators(RuleEngineClauseTransfer $ruleEngineClauseTransfer): bool
    {
        if ($this->comparatorChecker->isExistingComparator($ruleEngineClauseTransfer) === false) {
            throw new QueryStringException(sprintf(
                'Could not find value "%s" as comparator operator.',
                $ruleEngineClauseTransfer->getOperatorOrFail(),
            ));
        }

        return $this->comparatorChecker->isValidComparatorValue($ruleEngineClauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
     *
     * @return void
     */
    protected function validateField(
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
    ): void {
        $this->validateFieldNameFormat($ruleEngineClauseTransfer);
        $this->validateIfFieldIsRegistered($ruleEngineClauseTransfer, $ruleSpecificationProviderPlugin);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\QueryStringException
     *
     * @return void
     */
    protected function validateFieldNameFormat(RuleEngineClauseTransfer $ruleEngineClauseTransfer): void
    {
        $matches = preg_match(static::REGEX_FIELD_NAME, $ruleEngineClauseTransfer->getFieldOrFail());

        if ($matches === 0) {
            throw new QueryStringException(sprintf(
                'Invalid "%s" field name. Valid characters (a-z 0-9 . -).',
                $ruleEngineClauseTransfer->getFieldOrFail(),
            ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\QueryStringException
     *
     * @return void
     */
    protected function validateIfFieldIsRegistered(
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        RuleSpecificationProviderPluginInterface $ruleSpecificationProviderPlugin
    ): void {
        $clauseField = $ruleEngineClauseTransfer->getFieldOrFail();
        if ($ruleEngineClauseTransfer->getAttribute()) {
            $clauseField = $clauseField . '.' . $ruleEngineClauseTransfer->getAttributeOrFail();
        }

        $rulePlugins = $ruleSpecificationProviderPlugin->getRulePlugins();
        if ($this->metaDataProvider->isFieldAvailable($rulePlugins, $clauseField)) {
            return;
        }

        throw new QueryStringException(sprintf(
            'Could not found for field with name "%s".',
            $ruleEngineClauseTransfer->getFieldOrFail(),
        ));
    }
}
