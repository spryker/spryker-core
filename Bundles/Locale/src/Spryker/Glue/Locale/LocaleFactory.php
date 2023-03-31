<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface;

class LocaleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    public function getStoreClient(): LocaleToStoreClientInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::CLIENT_STORE);
    }
}
