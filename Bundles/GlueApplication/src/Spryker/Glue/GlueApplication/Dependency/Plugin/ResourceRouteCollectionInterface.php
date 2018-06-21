<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\Plugin;

interface ResourceRouteCollectionInterface
{
    /**
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function addGet(string $actionName, bool $isProtected = true, array $context = []): self;

    /**
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return \Spryker\Glue\GlueApplication\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function addPost(string $actionName, bool $isProtected = true, array $context = []): self;

    /**
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addDelete(string $actionName, bool $isProtected = true, array $context = []): self;

    /**
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addPatch(string $actionName, bool $isProtected = true, array $context = []): self;

    /**
     * @api
     *
     * @param string $method
     *
     * @return bool
     */
    public function has(string $method): bool;

    /**
     * @api
     *
     * @param string $method
     *
     * @return array
     */
    public function get(string $method): array;

    /**
     * @api
     *
     * @return array
     */
    public function getAvailableMethods(): array;
}
