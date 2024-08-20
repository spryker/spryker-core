<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router;

use Codeception\Actor;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\Router\PHPMD)
 */
class RouterBusinessTester extends Actor
{
    use _generated\RouterBusinessTesterActions;

    /**
     * @var int
     */
    protected const MODULE_ROOT_DIRECTORY_LEVEL = 5;

    /**
     * @var string
     */
    protected const CACHE_DIR = '/tests/_data/cache/';

    /**
     * @var string
     */
    protected const CACHE_FILE = 'url_generating_routes.php';

    /**
     * @return void
     */
    public function cleanCache(): void
    {
        $dirName = $this->getCacheDir();
        if (file_exists($dirName . static::CACHE_FILE)) {
            array_map('unlink', glob("$dirName/*.*", GLOB_NOSORT));
            rmdir($dirName);
        }
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        $moduleRoot = realpath(
            dirname(__DIR__, static::MODULE_ROOT_DIRECTORY_LEVEL),
        );

        return $moduleRoot . static::CACHE_DIR;
    }

    /**
     * @return string
     */
    public function getCacheFileName(): string
    {
        return $this->getCacheDir() . static::CACHE_FILE;
    }
}
