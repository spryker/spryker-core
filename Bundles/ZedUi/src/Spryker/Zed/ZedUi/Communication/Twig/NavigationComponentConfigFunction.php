<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Twig;

use Spryker\Shared\Twig\TwigFunction;

class NavigationComponentConfigFunction extends TwigFunction
{
    public const NAVIGATION_COMPONENT_CONFIG_FUNCTION_NAME = 'render_navigation_component_config';
    public const DEFAULT_ITEM_ICON = 'fa-angle-double-right';

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return self::NAVIGATION_COMPONENT_CONFIG_FUNCTION_NAME;
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function (array $navigationItems = []) {
            return json_encode($this->getMenuTree($navigationItems));
        };
    }

    /**
     * @param array $navigationItems
     *
     * @return array
     */
    protected function getMenuTree(array $navigationItems = []): array
    {
        $items = [];

        foreach ($navigationItems as $navigationItem) {
            $items[] = [
                'title' => $navigationItem['label'],
                'url' => $navigationItem['uri'],
                'icon' => isset($navigationItem['icon']) && empty($navigationItem['icon']) === false ? $navigationItem['icon'] : static::DEFAULT_ITEM_ICON,
                'isActive' => isset($navigationItem['is_active']) && (bool)$navigationItem['is_active'] ? true : false,
                'subItems' => isset($navigationItem['children']) && empty($navigationItem['children']) === false ? $this->getMenuTree($navigationItem['children']) : [],
            ];
        }

        return $items;
    }
}
