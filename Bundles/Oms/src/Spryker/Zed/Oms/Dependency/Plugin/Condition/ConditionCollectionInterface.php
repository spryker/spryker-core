<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin\Condition;

interface ConditionCollectionInterface
{
    /**
     *
     * Add new condition to list of conditions
     *
     * @api
     *
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface $condition
     * @param string $name
     *
     * @return $this
     */
    public function add($condition, $name);

    /**
     *
     * Return condition from list of conditions
     *
     * @api
     *
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface
     */
    public function get($name);
}
