<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxAppRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientBridge;

/**
 * @method \Spryker\Glue\TaxAppRestApi\TaxAppRestApiConfig getConfig()
 */
class TaxAppRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_TAX_APP = 'CLIENT_TAX_APP';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addTaxAppClient($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addTaxAppClient(Container $container): Container
    {
        $container->set(static::CLIENT_TAX_APP, function (Container $container) {
            return new TaxAppRestApiToTaxAppClientBridge(
                $container->getLocator()->taxApp()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container): TaxAppRestApiToGlossaryStorageClientInterface {
            return new TaxAppRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }
}
