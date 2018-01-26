<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client;

class CmsContentWidgetProductSearchConnectorToSearchClientBridge implements CmsContentWidgetProductSearchConnectorToSearchClientInterface
{
    /**
     * @var \Spryker\Client\Search\SearchClientInterface
     */
    protected $searchClient;

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet
     */
    public function searchSimpleString($searchString, $limit = null, $offset = null)
    {
        return $this->searchClient->searchSimpleString($searchString, $limit, $offset);
    }

    /**
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     */
    public function __construct($searchClient)
    {
        $this->searchClient = $searchClient;
    }
}
