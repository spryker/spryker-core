<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Stub;

use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceVersionableInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class TestVersionableResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, ResourceVersionableInterface
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
        $resourceRouteCollection->addGet('get')
            ->addPatch('patch')
            ->addDelete('delete')
            ->addPost('post');

        return $resourceRouteCollection;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return 'tests';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return 'test-resource';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return RestTestAttributesTransfer::class;
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\RestVersionTransfer
     */
    public function getVersion(): RestVersionTransfer
    {
        return new RestVersionTransfer();
    }
}
