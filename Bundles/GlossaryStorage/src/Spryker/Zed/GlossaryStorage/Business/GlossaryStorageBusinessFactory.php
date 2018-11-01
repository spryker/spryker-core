<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

use Spryker\Zed\GlossaryStorage\Business\Storage\GlossaryTranslationStorageWriter;
use Spryker\Zed\GlossaryStorage\GlossaryStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 */
class GlossaryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GlossaryStorage\Business\Storage\GlossaryTranslationStorageWriterInterface
     */
    public function createGlossaryTranslationStorageWriter()
    {
        return new GlossaryTranslationStorageWriter(
            $this->getQueryContainer(),
            $this->getUtilSanitizeService(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Dependency\Service\GlossaryStorageToUtilSanitizeServiceInterface
     */
    protected function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
