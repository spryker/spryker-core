<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin\Condition;

interface ConditionCollectionInterface
{

    /**
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface $condition
     * @param string $name
     *
     * @return $this
     */
    public function add(ConditionInterface $condition, $name);

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface
     */
    public function get($name);

}
