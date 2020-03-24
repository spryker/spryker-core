<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig;

/**
 * @method \Spryker\Glue\MerchantsRestApi\MerchantsRestApiFactory getFactory()
 */
class MerchantAddressesByMerchantReferenceResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `merchant-addresses` resource as a relationship by the resource id.
     * - Identifier of passed resources should contain `merchant` reference.
     *
     * @api
     *
     * @param array $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createMerchantAddressesByMerchantReferenceResourceRelationshipExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * @inheritDoc
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return MerchantsRestApiConfig::RESOURCE_MERCHANT_ADDRESSES;
    }
}
