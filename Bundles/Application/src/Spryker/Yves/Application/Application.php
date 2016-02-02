<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application;

use Spryker\Shared\Application\Communication\Application as SharedApplication;
use Spryker\Yves\Library\Session\TransferSession;
use Symfony\Component\HttpFoundation\Session\Session;

class Application extends SharedApplication
{

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this['session'];
    }

    /**
     * @return \Spryker\Yves\Library\Session\TransferSession
     */
    public function getTransferSession()
    {
        return new TransferSession($this['session']);
    }

    /**
     * @return \Spryker\Shared\Library\Storage\Adapter\KeyValue\ReadInterface
     */
    public function getStorageKeyValue()
    {
        return $this['storage.keyValue'];
    }

    /**
     * @return \Elastica\Client
     */
    public function getStorageElasticsearch()
    {
        return $this['storage.elasticsearch'];
    }

}
