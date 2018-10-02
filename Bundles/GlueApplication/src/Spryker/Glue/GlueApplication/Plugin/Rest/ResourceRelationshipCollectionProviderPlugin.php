<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class ResourceRelationshipCollectionProviderPlugin extends AbstractPlugin implements ResourceRelationshipCollectionProviderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Returns collection of plugins that are defined for GlueApplication on project level
     *
     * @api
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function getResourceRelationshipCollection(): ResourceRelationshipCollectionInterface
    {
        return $this->getFactory()->getResourceProviderPlugins();
    }
}
