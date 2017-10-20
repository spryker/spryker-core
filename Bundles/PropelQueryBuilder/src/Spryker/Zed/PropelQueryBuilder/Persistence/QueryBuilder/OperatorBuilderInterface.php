<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder;

interface OperatorBuilderInterface
{
    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface[]
     */
    public function buildOperators();

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\PropelQueryBuilder\Persistence\Exception\OperatorBuilderException
     *
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface
     */
    public function buildOperatorByType($type);

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\BeginsWith
     */
    public function buildBeginsWith();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Contains
     */
    public function buildContains();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\EndsWith
     */
    public function buildEndsWith();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Equal
     */
    public function buildEqual();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Greater
     */
    public function buildGreater();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\GreaterOrEqual
     */
    public function buildGreaterOrEqual();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\In
     */
    public function buildIn();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Less
     */
    public function buildLess();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\LessOrEqual
     */
    public function buildLessOrEqual();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotBeginsWith
     */
    public function buildNotBeginsWith();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotContains
     */
    public function buildNotContains();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotEndsWith
     */
    public function buildNotEndsWith();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotEqual
     */
    public function buildNotEqual();

    /**
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotIn
     */
    public function buildNotIn();
}
