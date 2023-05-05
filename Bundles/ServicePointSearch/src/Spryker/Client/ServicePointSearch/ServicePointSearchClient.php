<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ServicePointSearch\ServicePointSearchFactory getFactory()
 */
class ServicePointSearchClient extends AbstractClient implements ServicePointSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer>
     */
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): array
    {
        return $this->getFactory()
            ->createServicePointSearchReader()
            ->searchServicePoints($servicePointSearchRequestTransfer);
    }
}
