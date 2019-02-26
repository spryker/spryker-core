<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Collection;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouteCollection implements ResourceRouteCollectionInterface
{
    public const CONTROLLER_ACTION = 'action';
    public const METHOD_CONTEXT = 'context';
    public const IS_PROTECTED = 'is_protected';

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @param string $method
     *
     * @return bool
     */
    public function has(string $method): bool
    {
        return isset($this->actions[$method]);
    }

    /**
     * @param string $method
     *
     * @return array
     */
    public function get(string $method): array
    {
        return $this->actions[$method];
    }

    /**
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addGet(string $actionName, bool $isProtected = true, array $context = [])
    {
        $this->addAction(Request::METHOD_GET, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addPost(string $actionName, bool $isProtected = true, array $context = [])
    {
        $this->addAction(Request::METHOD_POST, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addDelete(string $actionName, bool $isProtected = true, array $context = [])
    {
        $this->addAction(Request::METHOD_DELETE, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @param string $actionName
     * @param bool $isProtected
     * @param array $context
     *
     * @return $this
     */
    public function addPatch(string $actionName, bool $isProtected = true, array $context = [])
    {
        $this->addAction(Request::METHOD_PATCH, $actionName, $isProtected, $context);

        return $this;
    }

    /**
     * @return array
     */
    public function getAvailableMethods(): array
    {
        return array_keys($this->actions);
    }

    /**
     * @param string $method
     * @param string $action
     * @param bool $isProtected
     * @param array $context
     *
     * @return void
     */
    protected function addAction(string $method, string $action, bool $isProtected, array $context): void
    {
        $this->actions[$method] = [
            static::CONTROLLER_ACTION => $action,
            static::METHOD_CONTEXT => $context,
            static::IS_PROTECTED => $isProtected,
        ];
    }
}
