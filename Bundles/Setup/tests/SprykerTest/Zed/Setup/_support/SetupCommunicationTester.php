<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Setup;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\Setup\PHPMD)
 */
class SetupCommunicationTester extends Actor
{
    use _generated\SetupCommunicationTesterActions;

    /**
     * @param string $pathToDirectory
     *
     * @return string
     */
    public function createTestFile(string $pathToDirectory): string
    {
        if (!is_dir($pathToDirectory)) {
            mkdir($pathToDirectory, 0777, true);
        }

        $pathToFile = sprintf('%s/%s', $pathToDirectory, 'foo.file');
        file_put_contents($pathToFile, 'content');

        return $pathToFile;
    }

    /**
     * @param string $pathToDirectory
     *
     * @return void
     */
    public function clearDirectory(string $pathToDirectory): void
    {
        if (is_dir($pathToDirectory)) {
            rmdir($pathToDirectory);
        }
    }
}
