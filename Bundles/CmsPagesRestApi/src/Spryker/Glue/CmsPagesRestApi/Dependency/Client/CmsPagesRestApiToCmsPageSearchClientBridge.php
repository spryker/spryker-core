<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Dependency\Client;

class CmsPagesRestApiToCmsPageSearchClientBridge implements CmsPagesRestApiToCmsPageSearchClientInterface
{
    /**
     * @var \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface
     */
    protected $cmsPageSearchClient;

    /**
     * @param \Spryker\Client\CmsPageSearch\CmsPageSearchClientInterface $cmsPageSearchClient
     */
    public function __construct($cmsPageSearchClient)
    {
        $this->cmsPageSearchClient = $cmsPageSearchClient;
    }

    /**
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters): array
    {
        return $this->cmsPageSearchClient->search($searchString, $requestParameters);
    }
}
