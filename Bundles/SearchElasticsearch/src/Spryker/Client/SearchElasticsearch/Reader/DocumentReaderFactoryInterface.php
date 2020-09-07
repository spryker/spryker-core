<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Reader;

use Elastica\Client;

/**
 * @deprecated Will be removed once the support of Elasticsearch 6 and lower is dropped.
 */
interface DocumentReaderFactoryInterface
{
    /**
     * @param \Elastica\Client $client
     *
     * @return \Spryker\Client\SearchElasticsearch\Reader\DocumentReaderInterface
     */
    public function createDocumentReader(Client $client): DocumentReaderInterface;
}
