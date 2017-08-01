<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\Composer\Updater;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Development\Business\Composer\Updater\AutoloadUpdater;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Composer
 * @group Updater
 * @group AutoloadUpdaterTest
 * Add your own group annotations below this line
 */
class AutoloadUpdaterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider autoloadKeys
     *
     * @param string $autoloadKey
     *
     * @return void
     */
    public function testWhenKeyInComposerButDirDoesNotExistRemoveFromComposer($autoloadKey)
    {
        $composerJson = $this->getComposerJson($autoloadKey);
        $splFileInfo = $this->getSplFile();

        $autoloadUpdaterMock = $this->getAutoloadUpdaterMock();
        $autoloadUpdaterMock->method('directoryExists')->willReturn(false);
        $updatedComposerJson = $autoloadUpdaterMock->update($composerJson, $splFileInfo);
        unset($composerJson['autoload']['psr-0'][$autoloadKey]);

        $this->assertSame($composerJson, $updatedComposerJson);
    }

    /**
     * @dataProvider autoloadKeys
     *
     * @param string $autoloadKey
     *
     * @return void
     */
    public function testWhenKeyInComposerAndDirExistMoveAutoloadKeyToDev($autoloadKey)
    {
        $composerJson = $this->getComposerJson($autoloadKey);
        $splFileInfo = $this->getSplFile();

        $autoloadUpdaterMock = $this->getAutoloadUpdaterMock();
        $autoloadUpdaterMock->method('directoryExists')->willReturnCallback(function ($path) use ($autoloadKey) {
            $testPath = __DIR__ . '/tests/' . $autoloadKey;

            return ($path === $testPath);
        });
        $updatedComposerJson = $autoloadUpdaterMock->update($composerJson, $splFileInfo);
        $composerJson['autoload-dev']['psr-0'][$autoloadKey] = $composerJson['autoload']['psr-0'][$autoloadKey];
        unset($composerJson['autoload']['psr-0'][$autoloadKey]);

        $this->assertSame($composerJson, $updatedComposerJson);
    }

    /**
     * @dataProvider autoloadKeys
     *
     * @param string $autoloadKey
     *
     * @return void
     */
    public function testWhenKeyNotInComposerButDirExistAddToComposer($autoloadKey)
    {
        $splFileInfo = $this->getSplFile();

        $composerJson = $this->getComposerJson($autoloadKey);
        unset($composerJson['autoload']['psr-0'][$autoloadKey]);
        $composerJson['autoload-dev']['psr-0'][$autoloadKey] = 'tests/';
        $autoloadUpdaterMock = $this->getAutoloadUpdaterMock();
        $autoloadUpdaterMock->method('directoryExists')->willReturnCallback(function ($path) use ($autoloadKey) {
            $testPath = __DIR__ . '/tests/' . $autoloadKey;

            return ($path === $testPath);
        });
        $updatedComposerJson = $autoloadUpdaterMock->update($composerJson, $splFileInfo);

        $this->assertSame($composerJson, $updatedComposerJson);
    }

    /**
     * @return array
     */
    public function autoloadKeys()
    {
        return [
            ['Acceptance'],
            ['Functional'],
            ['Integration'],
            ['Unit'],
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Development\Business\Composer\Updater\AutoloadUpdater
     */
    protected function getAutoloadUpdaterMock()
    {
        $autoloadUpdaterMock = $this->getMockBuilder(AutoloadUpdater::class)
            ->setMethods(['directoryExists'])
            ->getMock();

        return $autoloadUpdaterMock;
    }

    /**
     * @param string $autoloadKey
     *
     * @return array
     */
    protected function getComposerJson($autoloadKey)
    {
        return [
            'autoload' => [
                'psr-0' => [
                    'Path' => 'should/stay',
                    $autoloadKey => 'tests/',
                ],
            ],
        ];
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    protected function getSplFile()
    {
        return new SplFileInfo(__FILE__, __DIR__, __DIR__);
    }

}
