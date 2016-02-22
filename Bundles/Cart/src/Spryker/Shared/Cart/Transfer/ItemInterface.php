<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return $this
     */
    public function setId($identifier);

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity($quantity);

}
