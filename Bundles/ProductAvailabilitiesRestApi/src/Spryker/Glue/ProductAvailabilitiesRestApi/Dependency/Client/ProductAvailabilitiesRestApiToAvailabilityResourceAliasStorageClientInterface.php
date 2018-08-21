<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client;

use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;

interface ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract(string $sku): SpyAvailabilityAbstractEntityTransfer;
}
