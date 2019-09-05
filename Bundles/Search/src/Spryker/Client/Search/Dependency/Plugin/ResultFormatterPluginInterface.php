<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

/**
 * @deprecated Use `\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface` instead.
 */
interface ResultFormatterPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getName();

    /**
     * @api
     *
     * @param mixed $searchResult
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function formatResult($searchResult, array $requestParameters = []);
}
