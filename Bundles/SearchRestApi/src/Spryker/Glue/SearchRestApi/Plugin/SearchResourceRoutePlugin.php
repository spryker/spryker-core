<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Plugin;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SearchRestApi\SearchRestApiConfig;

/**
 * @method \Spryker\Glue\SearchRestApi\SearchRestApiFactory getFactory()
 */
class SearchResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {

        $resourceRouteCollection
            ->addGet('get');

        return $resourceRouteCollection;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return SearchRestApiConfig::RESOURCE_SEARCH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return 'search-resource';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestSearchRequestAttributesTransfer::class;
    }
}
