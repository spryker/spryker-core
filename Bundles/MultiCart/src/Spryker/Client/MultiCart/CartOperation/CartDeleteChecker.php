<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\CartOperation;

use Spryker\Client\MultiCart\Storage\MultiCartStorageInterface;

class CartDeleteChecker implements CartDeleteCheckerInterface
{
    /**
     * @var \Spryker\Client\MultiCart\Storage\MultiCartStorageInterface
     */
    protected $multiCartStorage;

    /**
     * @param \Spryker\Client\MultiCart\Storage\MultiCartStorageInterface $multiCartStorage
     */
    public function __construct(MultiCartStorageInterface $multiCartStorage)
    {
        $this->multiCartStorage = $multiCartStorage;
    }

    /**
     * @return bool
     */
    public function isQuoteDeletable(): bool
    {
        return $this->multiCartStorage->getQuoteCollection()->getQuotes()->count() > 1;
    }
}
