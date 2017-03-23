<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Dependency;

use Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface;

interface EventCollectionInterface
{

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface $eventListener
     * @param int $priority
     *
     * @return $this
     */
    public function addListener($eventName, EventListenerInterface $eventListener, $priority = 0);

    /**
     * @param string $eventName
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface $eventListener
     * @param int $priority
     *
     * @return $this
     */
    public function addListenerQueued($eventName, EventListenerInterface $eventListener, $priority = 0);

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function has($eventName);

    /**
     * @param string $eventName
     *
     * @throws \Spryker\Zed\Event\Business\Exception\EventListenerNotFoundException
     *
     * @return \Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface[]|\SplPriorityQueue
     */
    public function get($eventName);

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset);

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset);

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value);

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @since 5.0.0
     *
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     *
     * @return void
     */
    public function offsetUnset($offset);

}
