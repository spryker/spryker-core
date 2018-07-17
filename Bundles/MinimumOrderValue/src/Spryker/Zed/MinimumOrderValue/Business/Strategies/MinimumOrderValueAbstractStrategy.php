<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Strategies;

use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

abstract class MinimumOrderValueAbstractStrategy
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $group;

    /**
     * @param string $key
     *
     * @return $this
     */
    protected function setKey(string $key): MinimumOrderValueStrategyInterface
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $groupName
     *
     * @return $this
     */
    protected function setGroup(string $groupName): MinimumOrderValueStrategyInterface
    {
        $this->group = $groupName;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function toTransfer(): MinimumOrderValueTypeTransfer
    {
        return (new MinimumOrderValueTypeTransfer())
            ->setKey($this->getKey())
            ->setThresholdGroup($this->getGroup());
    }
}
