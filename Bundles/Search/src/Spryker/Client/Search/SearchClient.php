<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchClient extends AbstractClient implements SearchClientInterface
{

    /**
     * @api
     *
     * @deprecated This method will be removed, because it exposes a third party vendor.
     *
     * @return \Elastica\Index
     */
    public function getIndexClient()
    {
        // TODO: remove method usages
        trigger_error('This method will be removed.', E_USER_DEPRECATED);
        return $this->getFactory()->createIndexClient();
    }

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface $resultFormatter
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, ResultFormatterInterface $resultFormatter)
    {
        return $this
            ->getFactory()
            ->createElasticsearchSearchHandler()
            ->search($searchQuery, $resultFormatter);
    }

}
