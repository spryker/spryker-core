<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CartItemFixture extends AbstractTransfer
{
    /**
     * @var string
     */
    private $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setId($identifier)
    {
        $this->id = $identifier;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        // TODO: Implement getQuantity() method.
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity = 1)
    {
        // TODO: Implement setQuantity() method.
    }
}
