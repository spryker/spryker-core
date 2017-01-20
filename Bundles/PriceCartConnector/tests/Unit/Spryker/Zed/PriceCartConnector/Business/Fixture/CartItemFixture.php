<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\PriceCartConnector\Business\Fixture;

use Spryker\Shared\Cart\Transfer\ItemInterface;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CartItemFixture extends AbstractTransfer implements ItemInterface
{

    /**
     * @var string
     */
    private $id;

    public function __construct(LocatorLocatorInterface $locator = null)
    {
    }

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
