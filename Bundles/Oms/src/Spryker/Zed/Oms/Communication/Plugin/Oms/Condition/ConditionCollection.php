<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Condition;

use ArrayAccess;
use InvalidArgumentException;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface as DependencyConditionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\HasAwareCollectionInterface;
use Spryker\Zed\Oms\Exception\ConditionNotFoundException;

class ConditionCollection implements ConditionCollectionInterface, HasAwareCollectionInterface, ArrayAccess
{
    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface[]|\Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface[]
     */
    protected $conditions = [];

    /**
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface $condition
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function add($condition, $name)
    {
        if (!($condition instanceof DependencyConditionInterface) && !($condition instanceof ConditionInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Invalid condition '%s' interface!. Must implement '%s' or '%s'.",
                    get_class($condition),
                    DependencyConditionInterface::class,
                    ConditionInterface::class
                )
            );
        }

        $this->conditions[$name] = $condition;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->conditions[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface|\Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface
     */
    public function get($name)
    {
        if (empty($this->conditions[$name])) {
            throw new ConditionNotFoundException(
                sprintf('Could not find condition "%s". You need to add the needed conditions within your DependencyInjector.', $name)
            );
        }

        return $this->conditions[$name];
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param string $offset
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface|\Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $offset
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface|\Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value, $offset);
    }

    /**
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->conditions[$offset]);
    }
}
