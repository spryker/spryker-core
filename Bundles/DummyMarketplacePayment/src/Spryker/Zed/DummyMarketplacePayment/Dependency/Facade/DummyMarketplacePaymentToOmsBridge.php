<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Dependency\Facade;

class DummyMarketplacePaymentToOmsBridge implements DummyMarketplacePaymentToOmsInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param int $orderItemId
     * @param array $data
     *
     * @return array|null
     */
    public function triggerEventForOneOrderItem($eventId, $orderItemId, array $data = []): ?array
    {
        return $this->omsFacade->triggerEventForOneOrderItem($eventId, $orderItemId, $data);
    }
}
