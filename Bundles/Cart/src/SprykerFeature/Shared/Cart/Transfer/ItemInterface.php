<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Cart\Transfer;

interface ItemInterface
{

    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $identifier
     *
     * @return self
     */
    public function setId($identifier);

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @param int $quantity
     *
     * @return self
     */
    public function setQuantity($quantity);

}
