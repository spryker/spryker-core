<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Filterer;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiConfig;

class RequestTransferFilterer implements RequestTransferFiltererInterface
{
    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Api\ApiConfig $config
     */
    public function __construct(ApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function filter(ApiRequestTransfer $requestTransfer): ApiRequestTransfer
    {
        $requestTransfer = $this->filterServerData($requestTransfer);
        $requestTransfer = $this->filterHeaderData($requestTransfer);

        return $requestTransfer;
    }

    /**
     * @param array $data
     * @param array $allowedKeys
     *
     * @return array
     */
    protected function doFilter(array $data, $allowedKeys): array
    {
        return array_intersect_key($data, array_flip($allowedKeys));
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    protected function filterServerData(ApiRequestTransfer $requestTransfer): ApiRequestTransfer
    {
        $requestTransfer->setServerData(
            $this->doFilter(
                $requestTransfer->getServerData(),
                $this->config->getAllowedServerDataToBeLogged()
            )
        );

        return $requestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    protected function filterHeaderData(ApiRequestTransfer $requestTransfer): ApiRequestTransfer
    {
        $requestTransfer->setHeaderData(
            $this->doFilter(
                $requestTransfer->getHeaderData(),
                $this->config->getAllowedHeaderDataToBeLogged()
            )
        );

        return $requestTransfer;
    }
}
