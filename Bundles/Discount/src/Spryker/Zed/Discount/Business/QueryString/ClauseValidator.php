<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface;

class ClauseValidator implements ClauseValidatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparatorOperators;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface
     */
    protected $metaDataProvider;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparatorOperators
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface $metaDataProvider
     */
    public function __construct(
        ComparatorOperatorsInterface $comparatorOperators,
        MetaDataProviderInterface $metaDataProvider
    ) {
        $this->comparatorOperators = $comparatorOperators;
        $this->metaDataProvider = $metaDataProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    public function validateClause(ClauseTransfer $clauseTransfer)
    {
        $this->validateComparatorOperators($clauseTransfer);
        $this->validateField($clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return void
     */
    protected function validateComparatorOperators(ClauseTransfer $clauseTransfer)
    {
        if ($this->comparatorOperators->isExistingComparator($clauseTransfer) === false) {
            throw new QueryStringException(sprintf(
                'Could not find value "%s" as comparator operator.',
                $clauseTransfer->getOperator()
            ));
        }

        $this->comparatorOperators->isValidComparatorValue($clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return void
     */
    protected function validateField(ClauseTransfer $clauseTransfer)
    {
        $this->validateFieldNameFormat($clauseTransfer);
        $this->validateIfFieldIsRegistered($clauseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return void
     */
    protected function validateFieldNameFormat(ClauseTransfer $clauseTransfer)
    {
        $matches = preg_match('/^[a-z0-9\.\-]+$/i', $clauseTransfer->getField());

        if ($matches === 0) {
            throw new QueryStringException(
                sprintf(
                    'Invalid "%s" field name. Valid characters (a-z 0-9 . -).',
                    $clauseTransfer->getField()
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return void
     */
    protected function validateIfFieldIsRegistered(ClauseTransfer $clauseTransfer)
    {
        $clauseField = $clauseTransfer->getField();
        if ($clauseTransfer->getAttribute()) {
            $clauseField = $clauseField . '.' . $clauseTransfer->getAttribute();
        }

        if ($this->metaDataProvider->isFieldAvailable($clauseField)) {
            return;
        }

        throw new QueryStringException(sprintf(
            'Could not found for field with name "%s".',
            $clauseTransfer->getField()
        ));
    }
}
