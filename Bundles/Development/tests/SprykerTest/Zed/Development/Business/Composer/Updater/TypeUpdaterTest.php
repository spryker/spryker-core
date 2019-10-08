<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\Composer\Updater;

use Codeception\Test\Unit;
use Spryker\Zed\Development\Business\Composer\Updater\TypeUpdater;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group Composer
 * @group Updater
 * @group TypeUpdaterTest
 * Add your own group annotations below this line
 */
class TypeUpdaterTest extends Unit
{
    /**
     * @return void
     */
    public function testSetsTypeToPropelBehaviorWhenPackageNameContainsBehavior()
    {
        $typeUpdater = new TypeUpdater();
        $updatedJson = $typeUpdater->update($this->getComposerJson('foo-behavior'), $this->getSplFile());

        $this->assertSame('propel-behavior', $updatedJson['type']);
    }

    /**
     * @return void
     */
    public function testSetsTypeToLibraryBehaviorWhenPackageNameNotContainsBehavior()
    {
        $typeUpdater = new TypeUpdater();
        $updatedJson = $typeUpdater->update($this->getComposerJson('foo'), $this->getSplFile());

        $this->assertSame('library', $updatedJson['type']);
    }

    /**
     * @param string $packageName
     *
     * @return array
     */
    protected function getComposerJson(string $packageName): array
    {
        $composerArray = [
            'name' => sprintf('spryker/%s', $packageName),
            'type' => 'something',
        ];

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
