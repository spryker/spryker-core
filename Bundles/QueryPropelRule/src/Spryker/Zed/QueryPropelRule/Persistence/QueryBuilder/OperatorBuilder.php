<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder;

use Everon\Component\Collection\Collection;
use Spryker\Zed\QueryPropelRule\Persistence\Exception\OperatorBuilderException;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\BeginsWith;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Contains;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\EndsWith;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Equal;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Greater;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\GreaterOrEqual;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\In;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Less;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\LessOrEqual;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotBeginsWith;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotContains;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotEndsWith;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotEqual;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotIn;

class OperatorBuilder implements OperatorBuilderInterface
{

    /**
     * @var \Everon\Component\Collection\CollectionInterface
     */
    protected $operatorStorage;

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface[]
     */
    public function buildOperators()
    {
        return [
            $this->buildBeginsWith(),
            $this->buildContains(),
            $this->buildEndsWith(),
            $this->buildEqual(),
            $this->buildGreater(),
            $this->buildGreaterOrEqual(),
            $this->buildIn(),
            $this->buildLess(),
            $this->buildLessOrEqual(),
            $this->buildNotBeginsWith(),
            $this->buildNotContains(),
            $this->buildNotEndsWith(),
            $this->buildNotEqual(),
            $this->buildNotIn(),
        ];
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\QueryPropelRule\Persistence\Exception\OperatorBuilderException
     *
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface
     */
    public function buildOperatorByType($type)
    {
        $type = strtolower($type);
        if ($this->getOperatorStorage()->has($type)) {
            return $this->getOperatorStorage()->get($type);
        }

        throw new OperatorBuilderException(sprintf(
            'Invalid QueryBuilder operator type: %s',
            $type
        ));
    }

    /**
     * @return \Everon\Component\Collection\CollectionInterface
     */
    protected function getOperatorStorage()
    {
        if (!$this->operatorStorage) {
            $operators = $this->remapOperators($this->buildOperators());
            $this->operatorStorage = new Collection($operators);
        }

        return $this->operatorStorage;
    }

    /**
     * @param array $operatorCollection
     *
     * @throws \Spryker\Zed\QueryPropelRule\Persistence\Exception\OperatorBuilderException
     *
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface[]
     */
    protected function remapOperators(array $operatorCollection)
    {
        $operators = [];
        foreach ($operatorCollection as $operator) {
            $type = strtolower($operator->getType());

            if (array_key_exists($type, $operatorCollection)) {
                throw new OperatorBuilderException(sprintf(
                    'Operator "%s" is already defined',
                    $type
                ));
            }

            $operators[$type] = $operator;
        }

        return $operators;
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\BeginsWith
     */
    public function buildBeginsWith()
    {
        return new BeginsWith();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Contains
     */
    public function buildContains()
    {
        return new Contains();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\EndsWith
     */
    public function buildEndsWith()
    {
        return new EndsWith();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Equal
     */
    public function buildEqual()
    {
        return new Equal();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Greater
     */
    public function buildGreater()
    {
        return new Greater();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\GreaterOrEqual
     */
    public function buildGreaterOrEqual()
    {
        return new GreaterOrEqual();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\In
     */
    public function buildIn()
    {
        return new In();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\Less
     */
    public function buildLess()
    {
        return new Less();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\LessOrEqual
     */
    public function buildLessOrEqual()
    {
        return new LessOrEqual();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotBeginsWith
     */
    public function buildNotBeginsWith()
    {
        return new NotBeginsWith();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotContains
     */
    public function buildNotContains()
    {
        return new NotContains();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotEndsWith
     */
    public function buildNotEndsWith()
    {
        return new NotEndsWith();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotEqual
     */
    public function buildNotEqual()
    {
        return new NotEqual();
    }

    /**
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\NotIn
     */
    public function buildNotIn()
    {
        return new NotIn();
    }

}
