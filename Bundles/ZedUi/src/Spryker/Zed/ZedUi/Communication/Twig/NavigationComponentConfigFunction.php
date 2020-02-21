<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Twig;

use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\Twig\TwigFunction;

class NavigationComponentConfigFunction extends TwigFunction
{
    protected const NAVIGATION_COMPONENT_CONFIG_FUNCTION_NAME = 'render_navigation_component_config';
    protected const DEFAULT_ITEM_ICON = 'fa-angle-double-right';

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(UtilEncodingServiceInterface $utilEncodingService)
    {
        parent::__construct();
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::NAVIGATION_COMPONENT_CONFIG_FUNCTION_NAME;
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (array $navigationItems = []) {
            return $this->utilEncodingService->encodeJson($this->getMenuTree($navigationItems));
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
                'icon' => $this->getNavigationItemIcon($navigationItem),
                'isActive' => $this->isNavigationItemActive($navigationItem),
                'subItems' => $this->getNavigationItemSubItems($navigationItem),
            ];
        }

        return $items;
    }

    /**
     * @param array $navigationItem
     *
     * @return string
     */
    protected function getNavigationItemIcon(array $navigationItem): string
    {
        return isset($navigationItem['icon']) && empty($navigationItem['icon']) === false ? $navigationItem['icon'] : static::DEFAULT_ITEM_ICON;
    }

    /**
     * @param array $navigationItem
     *
     * @return array
     */
    protected function getNavigationItemSubItems(array $navigationItem): array
    {
        return isset($navigationItem['children']) && empty($navigationItem['children']) === false ? $this->getMenuTree($navigationItem['children']) : [];
    }

    /**
     * @param array $navigationItem
     *
     * @return bool
     */
    protected function isNavigationItemActive(array $navigationItem): bool
    {
        return isset($navigationItem['is_active']) && (bool)$navigationItem['is_active'];
    }
}
