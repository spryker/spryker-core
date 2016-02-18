<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

interface DiscountableInterface
{

    /**
     * @return float
     */
    public function getGrossPrice();

    /**
     * @return \ArrayObject
     */
    public function getDiscounts();

    /**
     * @param \ArrayObject $discountCollection
     *
     * @return $this
     */
    public function setDiscounts(\ArrayObject $discountCollection);

    /**
     * @return int
     */
    public function getQuantity();

}
