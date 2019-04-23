<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig;

use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Yves\Twig\TwigConfig getModuleConfig()
 *
 * @SuppressWarnings(PHPMD)
 */
class TwigYvesTester extends Actor
{
    use _generated\TwigYvesTesterActions;

    /**
     * @param array $templatePaths
     * @param array $expectedPaths
     *
     * @return void
     */
    public function assertPathsInOrder(array $templatePaths, array $expectedPaths)
    {
        $this->assertSame($expectedPaths, $templatePaths);
    }

    /**
     * @return string
     */
    public function getDefaultPathProjectWithStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Yves/%sDE/Theme/default';
    }

    /**
     * @return string
     */
    public function getDefaultPathProjectWithoutStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Yves/%s/Theme/default';
    }

    /**
     * @return string
     */
    public function getCustomPathProjectWithStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Yves/%sDE/Theme/custom';
    }

    /**
     * @return string
     */
    public function getCustomPathProjectWithoutStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Yves/%s/Theme/custom';
    }

    /**
     * @return string
     */
    public function getDefaultPathProjectSharedWithStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Shared/%sDE/Theme/default';
    }

    /**
     * @return string
     */
    public function getDefaultPathProjectSharedWithoutStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Shared/%s/Theme/default';
    }

    /**
     * @return string
     */
    public function getCustomPathProjectSharedWithStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Shared/%sDE/Theme/custom';
    }

    /**
     * @return string
     */
    public function getCustomPathProjectSharedWithoutStore(): string
    {
        return APPLICATION_ROOT_DIR . '/src/Foo/Shared/%s/Theme/custom';
    }

    /**
     * @return string
     */
    public function getPathSprykerShop(): string
    {
        return APPLICATION_VENDOR_DIR . '/*/*/src/SprykerShop/Yves/%s/Theme/default';
    }

    /**
     * @return string
     */
    public function getPathSprykerShopShared(): string
    {
        return APPLICATION_VENDOR_DIR . '/*/*/src/SprykerShop/Shared/%s/Theme/default';
    }

    /**
     * @return string
     */
    public function getPathSpryker(): string
    {
        return APPLICATION_VENDOR_DIR . '/*/*/src/Spryker/Yves/%s/Theme/default';
    }

    /**
     * @return string
     */
    public function getPathSprykerShared(): string
    {
        return APPLICATION_VENDOR_DIR . '/*/*/src/Spryker/Shared/%s/Theme/default';
    }
}
