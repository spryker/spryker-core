<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToQuoteClientInterface;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartConfig getConfig()
 */
class PersistentCartShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToQuoteClientInterface
     */
    public function getQuoteClient(): PersistentCartShareToQuoteClientInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::CLIENT_QUOTE);
    }
}
