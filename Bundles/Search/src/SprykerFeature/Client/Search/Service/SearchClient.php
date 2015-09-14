<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method ZedRequestDependencyContainer getDependencyContainer()
 */
class SearchClient extends AbstractClient
{

    /**
     * @return \Elastica\Index
     */
    public function getIndexClient()
    {
        return $this->getDependencyContainer()->createIndexClient();
    }

}
