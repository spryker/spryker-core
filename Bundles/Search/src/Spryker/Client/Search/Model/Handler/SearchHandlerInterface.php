<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Handler;

use Spryker\Client\Search\Model\Query\QueryInterface;

interface SearchHandlerInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $queryCriteria
     * @param \Spryker\Client\Search\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(QueryInterface $queryCriteria, array $resultFormatters, array $requestParameters = []);

}
