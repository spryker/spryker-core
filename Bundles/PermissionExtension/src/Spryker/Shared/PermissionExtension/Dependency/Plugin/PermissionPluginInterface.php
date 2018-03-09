<?php

namespace Spryker\Shared\PermissionExtension\Dependency\Plugin;


interface PermissionPluginInterface
{
    /**
     * Specification:
     * - Defines a permission plugin
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string;
}