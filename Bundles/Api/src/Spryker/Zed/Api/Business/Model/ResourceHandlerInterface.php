<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

interface ResourceHandlerInterface
{
    /**
     * @param string $resource
     * @param string $method
     * @param int|null $id
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ApiOptionsTransfer|\Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function execute($resource, $method, $id, array $params);
}
