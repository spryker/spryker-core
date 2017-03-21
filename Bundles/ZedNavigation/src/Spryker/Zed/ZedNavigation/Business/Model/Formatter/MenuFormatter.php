<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Formatter;

use Spryker\Shared\Url\UrlBuilderInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Validator\MenuLevelValidatorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Validator\UrlUniqueValidatorInterface;

class MenuFormatter implements MenuFormatterInterface
{

    const VISIBLE = 'visible';
    const URI = 'uri';
    const ID = 'id';
    const ATTRIBUTES = 'attributes';
    const LABEL = 'label';
    const PAGES = 'pages';
    const CONTROLLER = 'controller';
    const INDEX = 'index';
    const ACTION = 'action';
    const BUNDLE = 'bundle';
    const CHILDREN = 'children';
    const TITLE = 'title';
    const ICON = 'icon';
    const SHORTCUT = 'shortcut';
    const IS_ACTIVE = 'is_active';
    const CHILD_IS_ACTIVE = 'child_is_active';

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Validator\UrlUniqueValidatorInterface
     */
    protected $urlUniqueValidator;

    /**
     * @var \Spryker\Zed\ZedNavigation\Business\Model\Validator\MenuLevelValidatorInterface
     */
    protected $menuLevelValidator;

    /**
     * @var \Spryker\Shared\Url\UrlBuilderInterface
     */
    protected $urlBuilder;

    /**
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Validator\UrlUniqueValidatorInterface $urlUniqueValidator
     * @param \Spryker\Zed\ZedNavigation\Business\Model\Validator\MenuLevelValidatorInterface $menuLevelValidator
     * @param \Spryker\Shared\Url\UrlBuilderInterface $urlBuilder
     */
    public function __construct(
        UrlUniqueValidatorInterface $urlUniqueValidator,
        MenuLevelValidatorInterface $menuLevelValidator,
        UrlBuilderInterface $urlBuilder
    ) {
        $this->urlUniqueValidator = $urlUniqueValidator;
        $this->menuLevelValidator = $menuLevelValidator;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $pages
     * @param string $pathInfo
     * @param bool $includeInvisible
     *
     * @return array
     */
    public function formatMenu(array $pages, $pathInfo, $includeInvisible = false)
    {
        $formattedPages = $this->formatPages($pages, $pathInfo, 1, $includeInvisible);
        unset($formattedPages[self::CHILD_IS_ACTIVE]);

        return $formattedPages;
    }

    /**
     * @param array $pages
     * @param string $pathInfo
     * @param int $currentLevel
     * @param bool $includeInvisible
     *
     * @return array
     */
    protected function formatPages(array $pages, $pathInfo, $currentLevel = 1, $includeInvisible = false)
    {
        $formattedPages = [];
        $currentLevel++;
        foreach ($pages as $page) {
            if (!$includeInvisible && isset($page[self::VISIBLE]) && !$page[self::VISIBLE]) {
                continue;
            }
            $formattedPage = $this->formatPage($page);
            if (isset($page[self::PAGES]) && !empty($page[self::PAGES])) {
                $this->menuLevelValidator->validate($currentLevel, $page[self::TITLE]);
                $children = $this->formatPages($page[self::PAGES], $pathInfo, $currentLevel, $includeInvisible);
            }

            if (isset($children[self::CHILD_IS_ACTIVE]) || $pathInfo === $formattedPage[self::URI]) {
                $formattedPages[self::CHILD_IS_ACTIVE] = true;
                $formattedPage[self::IS_ACTIVE] = true;
            }
            if (!empty($children)) {
                unset($children[self::CHILD_IS_ACTIVE]);
                $formattedPage[self::CHILDREN] = $children;
                $children = [];
            }
            $formattedPages[$formattedPage[self::TITLE]] = $formattedPage;
        }

        return $formattedPages;
    }

    /**
     * @param array $page
     *
     * @return string
     */
    protected function getUri(array $page)
    {
        if (isset($page[self::URI]) && !empty($page[self::URI])) {
            return $page[self::URI];
        }

        $action = $this->getPageAction($page);
        $controller = $this->getPageController($page, $action);

        return $this->urlBuilder->build($page[self::BUNDLE], $controller, $action);
    }

    /**
     * @param array $page
     *
     * @return array
     */
    protected function formatPage(array $page)
    {
        $formattedPage = [];

        $url = $this->getUri($page);
        $formattedPage[self::URI] = $url;
        $formattedPage[self::LABEL] = $page[self::LABEL];
        $formattedPage[self::TITLE] = $page[self::TITLE];
        if (isset($page[self::ICON])) {
            $formattedPage[self::ICON] = $page[self::ICON];
        }

        if (isset($page[self::SHORTCUT]) && strlen($page[self::SHORTCUT]) === 1) {
            $formattedPage[self::SHORTCUT] = $page[self::SHORTCUT];
        }

        return $formattedPage;
    }

    /**
     * @param array $page
     *
     * @return mixed|null
     */
    protected function getPageAction(array $page)
    {
        $pageAction = null;
        if (isset($page[self::ACTION]) && self::INDEX !== $page[self::ACTION]) {
            $pageAction = $page[self::ACTION];
        }

        return $pageAction;
    }

    /**
     * @param array $page
     * @param mixed|null $action
     *
     * @return mixed|null
     */
    protected function getPageController(array $page, $action)
    {
        $pageController = null;
        if (isset($page[self::CONTROLLER]) &&
            (
                self::INDEX !== $page[self::CONTROLLER] || $action !== null
            )
        ) {
            $pageController = $page[self::CONTROLLER];
        }

        return $pageController;
    }

}
