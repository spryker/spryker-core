<?php

namespace Spryker\Zed\Permission\Communication\Plugin;

interface PermissionPluginInterface
{
    /**
     * Specification:
     * - The is used to identify the permission
     *
     * @return string
     */
    public function getKey();
}