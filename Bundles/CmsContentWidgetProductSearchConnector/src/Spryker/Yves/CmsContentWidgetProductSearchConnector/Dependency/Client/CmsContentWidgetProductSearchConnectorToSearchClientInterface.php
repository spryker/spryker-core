<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client;

interface CmsContentWidgetProductSearchConnectorToSearchClientInterface
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return mixed
     */
    public function searchQueryString($searchString, $limit = null, $offset = null);
}
