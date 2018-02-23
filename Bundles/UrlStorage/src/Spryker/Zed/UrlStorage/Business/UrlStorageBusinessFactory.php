<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\UrlStorage\Business\Storage\RedirectStorageWriter;
use Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriter;
use Spryker\Zed\UrlStorage\UrlStorageDependencyProvider;

/**
 * @method \Spryker\Zed\UrlStorage\UrlStorageConfig getConfig()
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface getQueryContainer()
 */
class UrlStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriterInterface
     */
    public function createUrlStorageWriter()
    {
        return new UrlStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\UrlStorage\Business\Storage\RedirectStorageWriterInterface
     */
    public function createRedirectStorageWriter()
    {
        return new RedirectStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface
     */
    protected function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
