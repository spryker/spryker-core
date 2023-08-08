<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;

class ServicePointsRestApiToServicePointSearchClientBridge implements ServicePointsRestApiToServicePointSearchClientInterface
{
    /**
     * @var \Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface
     */
    protected $servicePointSearchClient;

    /**
     * @param \Spryker\Client\ServicePointSearch\ServicePointSearchClientInterface $servicePointSearchClient
     */
    public function __construct($servicePointSearchClient)
    {
        $this->servicePointSearchClient = $servicePointSearchClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer>
     */
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): array
    {
        return $this->servicePointSearchClient->searchServicePoints($servicePointSearchRequestTransfer);
    }
}
