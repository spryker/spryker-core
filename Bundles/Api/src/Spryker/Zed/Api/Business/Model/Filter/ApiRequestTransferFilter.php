<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Filter;

use Generated\Shared\Transfer\ApiRequestTransfer;

class ApiRequestTransferFilter implements ApiRequestTransferFilterInterface
{
    /**
     * @var \Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface[]
     */
    protected $apiRequestTransferFilterPlugins;

    /**
     * @param \Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface[] $apiRequestTransferFilterPlugins
     */
    public function __construct(array $apiRequestTransferFilterPlugins)
    {
        $this->apiRequestTransferFilterPlugins = $apiRequestTransferFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function filter(ApiRequestTransfer $apiRequestTransfer): ApiRequestTransfer
    {
        foreach ($this->apiRequestTransferFilterPlugins as $apiRequestTransferFilterPlugin) {
            $apiRequestTransfer = $apiRequestTransferFilterPlugin->filter($apiRequestTransfer);
        }

        return $apiRequestTransfer;
    }
}
