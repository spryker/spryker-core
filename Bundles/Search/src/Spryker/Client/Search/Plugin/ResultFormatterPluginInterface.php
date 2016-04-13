<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin;

interface ResultFormatterPluginInterface
{

    /**
     * @param mixed $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    public function formatResult($searchResult, array $requestParameters = []);

}
