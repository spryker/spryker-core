<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Dependency\Facade;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CustomerApiToApiFacadeBridge implements CustomerApiToApiFacadeInterface
{
    /**
     * @var \Spryker\Zed\Api\Business\ApiFacadeInterface
     */
    protected $apiFacade;

    /**
     * @param \Spryker\Zed\Api\Business\ApiFacadeInterface $apiFacade
     */
    public function __construct($apiFacade)
    {
        $this->apiFacade = $apiFacade;
    }

    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer> $transfers
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $transfers): ApiCollectionTransfer
    {
        return $this->apiFacade->createApiCollection($transfers);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $transfer
     * @param string|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem(?AbstractTransfer $transfer = null, ?string $id = null): ApiItemTransfer
    {
        return $this->apiFacade->createApiItem($transfer, $id);
    }
}
