<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search;

interface SearchClientInterface
{

    /**
     * @return \Elastica\Index
     */
    public function getIndexClient();

}
