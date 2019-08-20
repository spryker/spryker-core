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
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     */
    public function __construct($searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
     */
    public function searchQueryString($searchString, $limit = null, $offset = null)
    {
        return $this->searchClient->searchQueryString($searchString, $limit, $offset);
    }
}
