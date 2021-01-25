<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use RuntimeException;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CartItemFixture extends AbstractTransfer
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setId(string $identifier)
    {
        $this->id = $identifier;

        return $this;
    }

    /**
     * @throws \RuntimeException
     *
     * @return int
     */
    public function getQuantity(): int
    {
        throw new RuntimeException('Implement getQuantity() method');
    }

    /**
     * @param int $quantity
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function setQuantity(int $quantity = 1)
    {
        throw new RuntimeException('Implement setQuantity() method');
    }
}
