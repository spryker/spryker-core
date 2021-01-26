<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

interface ResourceRouteCollectionInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addGet(string $actionName, bool $isProtected = true, array $context = []);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addPost(string $actionName, bool $isProtected = true, array $context = []);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addDelete(string $actionName, bool $isProtected = true, array $context = []);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addPatch(string $actionName, bool $isProtected = true, array $context = []);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $method
     *
     * @return bool
     */
    public function has(string $method): bool;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $method
     *
     * @return array
     */
    public function get(string $method): array;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return array
     */
    public function getAvailableMethods(): array;
}
