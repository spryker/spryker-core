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
    protected $name;

    /**
     * @var string
     */
    protected $group;

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function setName(string $name): MinimumOrderValueStrategyInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
            ->setName($this->getName());
    }
}
