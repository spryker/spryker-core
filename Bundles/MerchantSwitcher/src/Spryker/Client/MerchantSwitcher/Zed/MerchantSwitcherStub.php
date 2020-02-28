<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSwitcher\Zed;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;
use Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToZedRequestClientInterface;

class MerchantSwitcherStub implements MerchantSwitcherStubInterface
{
    /**
     * @var \Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(MerchantSwitcherToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\MerchantSwitcher\Communication\Controller\GatewayController::switchMerchantInQuoteAction()
     *
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuote(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantSwitchResponseTransfer $merchantSwitchResponseTransfer */
        $merchantSwitchResponseTransfer = $this->zedRequestClient->call('/merchant-switcher/gateway/switch-merchant-in-quote', $merchantSwitchRequestTransfer);

        return $merchantSwitchResponseTransfer;
    }
}
