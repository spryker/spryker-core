<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Handler;

use Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface PluginHandlerInterface
{
    /**
     * @return array
     */
    public function getGeneratedPaths(): array;

    /**
     * @return array
     */
    public function getGeneratedSchemas(): array;

    /**
     * @return array
     */
    public function getGeneratedSecuritySchemes(): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param string $idResource
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addGetResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, string $idResource, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPostResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addPatchResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param string $resourcePath
     * @param bool $isProtected
     * @param \Generated\Shared\Transfer\RestApiDocumentationAnnotationTransfer|null $annotationTransfer
     *
     * @return void
     */
    public function addDeleteResourcePath(ResourceRoutePluginInterface $plugin, string $resourcePath, bool $isProtected, ?RestApiDocumentationAnnotationTransfer $annotationTransfer): void;
}
