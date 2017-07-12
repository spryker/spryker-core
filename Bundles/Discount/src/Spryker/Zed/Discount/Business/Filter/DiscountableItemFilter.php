<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Filter;

use Generated\Shared\Transfer\CollectedDiscountTransfer;

class DiscountableItemFilter implements DiscountableItemFilterInterface
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface[]
     */
    protected $discountableItemFilterPlugins = [];

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountableItemFilterPluginInterface[] $discountableItemFilterPlugins
     */
    public function __construct(array $discountableItemFilterPlugins)
    {
        $this->discountableItemFilterPlugins = $discountableItemFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filter(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        foreach ($this->discountableItemFilterPlugins as $discountableItemFilterPlugin) {
            $collectedDiscountTransfer = $discountableItemFilterPlugin->filter($collectedDiscountTransfer);
        }

        return $collectedDiscountTransfer;
    }

}
