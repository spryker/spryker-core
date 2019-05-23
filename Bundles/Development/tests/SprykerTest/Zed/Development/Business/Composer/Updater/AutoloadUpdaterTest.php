<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\Composer\Updater;

use Codeception\Test\Unit;
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
class AutoloadUpdaterTest extends Unit
{
    /**
     * @return void
     */
    public function testWhenTestsFolderExistsDefaultAutoloadDevIsAddedToComposer()
    {
        $updatedJson = $this->updateJsonForTests($this->getComposerJson());
        $this->assertSame($this->getComposerJson()['autoload-dev'], $updatedJson['autoload-dev']);
    }

    /**
     * @dataProvider autoloadKeys
     *
     * @param string $autoloadKey
     *
     * @return void
     */
    public function testWhenDeprecatedDirExistsAutoloadDevAddedToComposer($autoloadKey)
    {
        $updatedJson = $this->getJsonAfterUpdate(
            [
                AutoloadUpdater::BASE_TESTS_DIRECTORY,
                $autoloadKey,
            ],
            $this->getComposerJson($autoloadKey)
        );
        $this->assertSame($this->getComposerJson($autoloadKey)['autoload-dev'], $updatedJson['autoload-dev']);
    }

    /**
     * @return void
     */
    public function testWhenTestFolderDoesNotExistNothingAddedToComposer()
    {
        $splFileInfo = $this->getSplFile();
        $composerJson = $this->getComposerJson();
        $autoloadUpdaterMock = $this->getAutoloadUpdaterMock();
        $autoloadUpdaterMock->method('pathExists')->willReturn(false);

        $updatedComposerJson = $autoloadUpdaterMock->update($composerJson, $splFileInfo);
        $this->assertArrayNotHasKey('autoload', $updatedComposerJson, 'autoload empty and thus removed.');
        $this->assertArrayNotHasKey('autoload-dev', $updatedComposerJson, 'autoload-dev empty and thus removed.');
    }

    /**
     * @return void
     */
    public function testWhenAutoloadDevNamespaceIsInvalidGetsRemoved()
    {
        $composerJson = $this->getComposerJson();
        $composerJson['autoload-dev']['psr-4']['invalidNamespace'] = 'validDirectory/';

        $updatedJson = $this->updateJsonForTests($composerJson);

        $this->assertSame($this->getComposerJson()['autoload-dev'], $updatedJson['autoload-dev']);
    }

    /**
     * @return void
     */
    public function testWhenAutoloadPathIsInvalidGetsRemoved()
    {
        $composerJson = $this->getComposerJson();
        $composerJson['autoload']['psr-4']['validNamespace'] = 'invalidDirectory/';

        $updatedJson = $this->updateJsonForTests($composerJson);

        $this->assertSame($this->getComposerJson()['autoload-dev'], $updatedJson['autoload-dev']);
    }

    /**
     * @return void
     */
    public function testWhenSupportFolderExistsWithHelpersItGetsAddedToAutoload()
    {
        $pathParts = [
            AutoloadUpdater::BASE_SRC_DIRECTORY,
            AutoloadUpdater::SPRYKER_NAMESPACE,
        ];

        $updatedJson = $this->getJsonAfterUpdate(
            [
                AutoloadUpdater::BASE_SRC_DIRECTORY . '/' . AutoloadUpdater::SPRYKER_NAMESPACE,
            ],
            $this->getComposerJson(),
            [
                [
                    $pathParts,
                    implode($pathParts, DIRECTORY_SEPARATOR) . '/',
                ],
            ]
        );

        $this->assertSame($this->getComposerJson()['autoload'], $updatedJson['autoload']);
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function updateJsonForTests(array $composerJson)
    {
        $pathParts = [
            AutoloadUpdater::BASE_TESTS_DIRECTORY,
            AutoloadUpdater::SPRYKER_TEST_NAMESPACE,
        ];

        return $this->getJsonAfterUpdate(
            [
                AutoloadUpdater::BASE_TESTS_DIRECTORY,
                AutoloadUpdater::SPRYKER_TEST_NAMESPACE,
            ],
            $composerJson,
            [
                [
                    $pathParts,
                    implode($pathParts, DIRECTORY_SEPARATOR) . '/',
                ],
            ]
        );
    }

    /**
     * @param array $pathParts
     * @param array $composerJson
     * @param array $dirMapAdditions
     *
     * @return array
     */
    protected function getJsonAfterUpdate(array $pathParts, array $composerJson, array $dirMapAdditions = [])
    {
        $splFileInfo = $this->getSplFile();

        $pathExistsMap = [
            ['pass', true],
        ];

        $dirMap = [
            [
                array_merge([dirname($splFileInfo->getPathname())], $pathParts),
                'pass',
            ],
        ];

        if (!empty($dirMapAdditions)) {
            $dirMap = array_merge($dirMap, $dirMapAdditions);
        }

        $autoloadUpdaterMock = $this->getAutoloadUpdaterMock();
        $autoloadUpdaterMock->method('pathExists')->will($this->returnValueMap($pathExistsMap));
        $autoloadUpdaterMock->method('getPath')->will($this->returnValueMap($dirMap));

        return $autoloadUpdaterMock->update($composerJson, $splFileInfo);
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Development\Business\Composer\Updater\AutoloadUpdater
     */
    protected function getAutoloadUpdaterMock()
    {
        $autoloadUpdaterMock = $this->getMockBuilder(AutoloadUpdater::class)
            ->setMethods(['pathExists', 'getPath'])
            ->getMock();

        return $autoloadUpdaterMock;
    }

    /**
     * @param string $autoloadKey
     *
     * @return array
     */
    protected function getComposerJson($autoloadKey = '')
    {
        $composerArray = [
            'autoload' => [
                'psr-4' => [
                    'Spryker' => 'src/Spryker',
                ],
            ],
            'autoload-dev' => [
                'psr-4' => [
                    'SprykerTest\\' => 'tests/SprykerTest/',
                ],
            ],
        ];

        if (!empty($autoloadKey)) {
            $composerArray['autoload-dev']['psr-0'] = [$autoloadKey => 'tests/'];
            unset($composerArray['autoload-dev']['psr-4']);
        }

        return $composerArray;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    protected function getSplFile()
    {
        return new SplFileInfo(__FILE__, __DIR__, __DIR__);
    }
}
