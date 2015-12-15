<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application;

use Elastica\Client;
use Spryker\Shared\Application\Communication\Application as SharedApplication;
use Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use Spryker\Yves\Library\Session\TransferSession;
use Symfony\Component\HttpFoundation\Session\Session;

class Application extends SharedApplication
{

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this['session'];
    }

    /**
     * @return TransferSession
     */
    public function getTransferSession()
    {
        return new TransferSession($this['session']);
    }

    /**
     * @return ReadInterface
     */
    public function getStorageKeyValue()
    {
        return $this['storage.keyValue'];
    }

    /**
     * @return Client
     */
    public function getStorageElasticsearch()
    {
        return $this['storage.elasticsearch'];
    }

}
