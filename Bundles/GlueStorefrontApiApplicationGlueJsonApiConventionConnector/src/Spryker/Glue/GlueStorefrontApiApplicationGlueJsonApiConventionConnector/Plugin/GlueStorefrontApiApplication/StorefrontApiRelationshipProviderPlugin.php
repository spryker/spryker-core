<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Plugin\GlueStorefrontApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\GlueStorefrontApiApplicationGlueJsonApiConventionConnectorFactory getFactory()
 */
class StorefrontApiRelationshipProviderPlugin extends AbstractPlugin implements RelationshipProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Returns true if current request is coming from the storefront API application.
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return $glueRequestTransfer->getApplication() === static::GLUE_STOREFRONT_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Returns a collection of relationship plugins wired for the storefront API application.
     *
     * @api
     *
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function getResourceRelationshipCollection(): ResourceRelationshipCollectionInterface
    {
        return $this->getFactory()->getResourceProviderPlugins();
    }
}
