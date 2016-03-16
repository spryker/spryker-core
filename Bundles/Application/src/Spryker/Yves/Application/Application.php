<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application;

use Spryker\Shared\Application\Communication\Application as SharedApplication;
use Spryker\Yves\Library\Session\TransferSession;

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
