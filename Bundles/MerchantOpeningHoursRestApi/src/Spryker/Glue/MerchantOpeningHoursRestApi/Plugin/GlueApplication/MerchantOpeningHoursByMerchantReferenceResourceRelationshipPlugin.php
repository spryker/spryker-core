<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig;

/**
 * @method \Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiFactory getFactory()
 */
class MerchantOpeningHoursByMerchantReferenceResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @inheritDoc
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createMerchantOpeningHoursByMerchantReferenceResourceRelationshipExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * @inheritDoc
     */
    public function getRelationshipResourceType(): string
    {
        return MerchantOpeningHoursRestApiConfig::RESOURCE_MERCHANT_OPENING_HOURS;
    }
}
