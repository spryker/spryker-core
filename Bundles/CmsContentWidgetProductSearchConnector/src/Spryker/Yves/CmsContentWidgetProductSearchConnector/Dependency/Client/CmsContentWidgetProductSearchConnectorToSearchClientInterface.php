<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client;

interface CmsContentWidgetProductSearchConnectorToSearchClientInterface
{
    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|mixed
     */
    public function searchQueryString($searchString, $limit = null, $offset = null);
}
