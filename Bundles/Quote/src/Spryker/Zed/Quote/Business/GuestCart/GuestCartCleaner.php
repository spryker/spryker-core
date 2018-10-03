<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\GuestCart;

use DateInterval;
use DateTime;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\QuoteConfig;

class GuestCartCleaner implements GuestCartCleanerInterface
{
    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Quote\QuoteConfig $config
     */
    public function __construct(QuoteEntityManagerInterface $entityManager, QuoteConfig $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function cleanExpiredGuestCart(): void
    {
        $lifetime = $this->config->getGuestCartLifetime();
        $lifetimeLimitDate = (new DateTime())->sub(new DateInterval($lifetime));

        $this->entityManager->cleanExpiredGuestCart($lifetimeLimitDate);
    }
}
