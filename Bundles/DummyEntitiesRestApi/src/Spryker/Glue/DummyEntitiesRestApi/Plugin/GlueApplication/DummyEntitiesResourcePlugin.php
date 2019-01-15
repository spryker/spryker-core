<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestDummyEntityAttributesTransfer;
use Spryker\Glue\DummyEntitiesRestApi\DummyEntitiesRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class DummyEntitiesResourcePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritdoc}
     *   - Configures available actions for dummy-entities resource.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(
        ResourceRouteCollectionInterface $resourceRouteCollection
    ): ResourceRouteCollectionInterface {
        $resourceRouteCollection
            ->addGet(DummyEntitiesRestApiConfig::ACTION_DUMMY_ENTITIES_GET)
            ->addPost(DummyEntitiesRestApiConfig::ACTION_DUMMY_ENTITIES_POST)
            ->addPatch(DummyEntitiesRestApiConfig::ACTION_DUMMY_ENTITIES_PATCH)
            ->addDelete(DummyEntitiesRestApiConfig::ACTION_DUMMY_ENTITIES_DELETE);

        return $resourceRouteCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return DummyEntitiesRestApiConfig::RESOURCE_DUMMY_ENTITIES;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return DummyEntitiesRestApiConfig::CONTROLLER_DUMMY_ENTITIES;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestDummyEntityAttributesTransfer::class;
    }
}
