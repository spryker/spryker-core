<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Plugin;

use Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomerForgottenPasswordResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
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
            ->addPost('post', false);

        return $resourceRouteCollection;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return CustomersRestApiConfig::RESOURCE_FORGOTTEN_PASSWORD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return 'customer-forgotten-password-resource';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestCustomerForgottenPasswordAttributesTransfer::class;
    }
}
