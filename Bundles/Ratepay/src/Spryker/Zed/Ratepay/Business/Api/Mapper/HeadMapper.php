<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\RatepayRequestHeadTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\RatepayConfig;

class HeadMapper extends BaseMapper
{
    /**
     * @var \Spryker\Zed\Ratepay\RatepayConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @param \Spryker\Zed\Ratepay\RatepayConfig $config
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     */
    public function __construct(
        RatepayConfig $config,
        RatepayRequestTransfer $requestTransfer
    ) {
        $this->config = $config;
        $this->requestTransfer = $requestTransfer;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->requestTransfer->setHead(new RatepayRequestHeadTransfer())->getHead()
            ->setSystemId($this->config->getSystemId())
            ->setProfileId($this->config->getProfileId())
            ->setSecurityCode($this->config->getSecurityCode());
    }
}
