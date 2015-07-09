<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;
use SprykerEngine\Shared\Transfer\AbstractTransfer;

class CartItemFixture extends AbstractTransfer implements ItemInterface
{

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
