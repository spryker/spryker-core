<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Discount\Business\QueryString\Specification\MetaData;

interface MetaDataProviderInterface
{

    /**
     * @return array|string[]
     */
    public function getAvailableFields();

    /**
     * @param string $fieldName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return array|\string[]
     */
    public function getAcceptedTypesByFieldName($fieldName);

    /**
     * @param string $fieldName
     *
     * @return array|string[]
     */
    public function getAvailableOperatorExpressionsForField($fieldName);

    /**
     * @return array|string[]
     */
    public function getAvailableComparatorExpressions();

    /**
     * @return array|string[]
     */
    public function getLogicalComparators();

    /**
     * @return array|string[]
     */
    public function getCompoundExpressions();

}
