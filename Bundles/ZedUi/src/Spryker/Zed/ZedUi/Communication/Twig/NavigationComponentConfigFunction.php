<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Twig;

use Spryker\Shared\Twig\TwigFunction;
use Spryker\Zed\ZedUi\Dependency\Facade\ZedUiToTranslatorFacadeInterface;
use Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface;

class NavigationComponentConfigFunction extends TwigFunction
{
    protected const NAVIGATION_COMPONENT_CONFIG_FUNCTION_NAME = 'render_navigation_component_config';
    protected const DEFAULT_ITEM_ICON = 'fa-angle-double-right';

    /**
     * @var \Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ZedUi\Dependency\Facade\ZedUiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ZedUi\Dependency\Service\ZedUiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ZedUi\Dependency\Facade\ZedUiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ZedUiToUtilEncodingServiceInterface $utilEncodingService,
        ZedUiToTranslatorFacadeInterface $translatorFacade
    ) {
        parent::__construct();
        $this->utilEncodingService = $utilEncodingService;
        $this->translatorFacade = $translatorFacade;
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
        return function (array $navigationItems = []): ?string {
            $menuTree = $this->getMenuTree($navigationItems);

            return $this->utilEncodingService->encodeJson($menuTree);
        };
    }

    /**
     * @param array $navigationItems
     *
     * @return \Spryker\Zed\ZedUi\Communication\Twig\NavigationItem[]
     */
    protected function getMenuTree(array $navigationItems = []): array
    {
        $items = [];

        foreach ($navigationItems as $navigationItem) {
            $items[] = $this->createNavigationItem($navigationItem);
        }

        return $items;
    }

    /**
     * @param array $item
     *
     * @return \Spryker\Zed\ZedUi\Communication\Twig\NavigationItem
     */
    protected function createNavigationItem(array $item): NavigationItem
    {
        $navigationItem = new NavigationItem();

        $navigationItem->setTitle($this->translatorFacade->trans($item['label']));
        $navigationItem->setUrl($item['uri']);
        $navigationItem->setIcon($this->getNavigationItemIcon($item));
        $navigationItem->setIsActive($this->isNavigationItemActive($item));
        $navigationItem->setSubItems($this->getNavigationItemSubItems($item));

        return $navigationItem;
    }

    /**
     * @param array $navigationItem
     *
     * @return string
     */
    protected function getNavigationItemIcon(array $navigationItem): string
    {
        return !empty($navigationItem['icon']) ? $navigationItem['icon'] : static::DEFAULT_ITEM_ICON;
    }

    /**
     * @param array $navigationItem
     *
     * @return array
     */
    protected function getNavigationItemSubItems(array $navigationItem): array
    {
        return !empty($navigationItem['children']) ? $this->getMenuTree($navigationItem['children']) : [];
    }

    /**
     * @param array $navigationItem
     *
     * @return bool
     */
    protected function isNavigationItemActive(array $navigationItem): bool
    {
        return (bool)($navigationItem['is_active'] ?? false);
    }
}
