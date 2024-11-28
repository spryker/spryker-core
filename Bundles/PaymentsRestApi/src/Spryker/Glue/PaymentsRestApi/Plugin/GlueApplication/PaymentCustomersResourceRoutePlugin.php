<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestPaymentCustomersRequestAttributesTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;

class PaymentCustomersResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        $resourceRouteCollection->addPost('post', false);

        return $resourceRouteCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return PaymentsRestApiConfig::RESOURCE_TYPE_PAYMENT_CUSTOMERS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return 'payment-customers-resource';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestPaymentCustomersRequestAttributesTransfer::class;
    }
}
