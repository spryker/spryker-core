<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface;
use Spryker\Glue\UrlsRestApi\Processor\Expander\CategoryNodeNavigationsResourceExpander;
use Spryker\Glue\UrlsRestApi\Processor\Expander\CategoryNodeNavigationsResourceExpanderInterface;

class UrlsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\UrlsRestApi\Processor\Expander\CategoryNodeNavigationsResourceExpanderInterface
     */
    public function createCategoryNodeNavigationResourceExpander(): CategoryNodeNavigationsResourceExpanderInterface
    {
        return new CategoryNodeNavigationsResourceExpander($this->getUrlStorageClient());
    }

    /**
     * @return \Spryker\Glue\UrlsRestApi\Dependency\Client\UrlsRestApiToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): UrlsRestApiToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(UrlsRestApiDependencyProvider::CLIENT_URL_STORAGE);
    }
}
