<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Business;

use Elastica\Client;
use SprykerFeature\Shared\Application\Business\Application as SharedApplication;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerFeature\Yves\Library\Session\TransferSession;
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
