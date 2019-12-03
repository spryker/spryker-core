<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\MetaData;

interface MetaDataProviderInterface
{
    /**
     * @return string[]
     */
    public function getAvailableFields();

    /**
     * @param string $field
     *
     * @return bool
     */
    public function isFieldAvailable($field);

    /**
     * @param string $fieldName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return string[]
     */
    public function getAcceptedTypesByFieldName($fieldName);

    /**
     * @param string $fieldName
     *
     * @return string[]
     */
    public function getAvailableOperatorExpressionsForField($fieldName);

    /**
     * @return string[]
     */
    public function getAvailableComparatorExpressions();

    /**
     * @return string[]
     */
    public function getLogicalComparators();

    /**
     * @return string[]
     */
    public function getCompoundExpressions();

    /**
     * @return array
     */
    public function getQueryStringValueOptions();
}
