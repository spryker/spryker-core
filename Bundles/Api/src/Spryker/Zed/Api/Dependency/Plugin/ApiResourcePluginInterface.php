<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Dependency\Plugin;

use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface ApiResourcePluginInterface
{

    /**
     * @api
     *
     * @return string
     */
    public function getResourceName();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer);

    /**
     * @api
     *
     * @param int $id
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($id, ApiFilterTransfer $apiFilterTransfer);

}
