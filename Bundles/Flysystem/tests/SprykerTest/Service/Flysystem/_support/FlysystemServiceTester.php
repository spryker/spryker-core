<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Flysystem;

use Codeception\Actor;
use Codeception\Stub;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface;

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
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Service\Flysystem\FlysystemService getService()
 */
class FlysystemServiceTester extends Actor
{
    use _generated\FlysystemServiceTesterActions;

    /**
     * @return void
     */
    public function arrangeFilesystemProviderThatReturnsDataWithPropertiesThatAreNotPresentInTheFlysystemResourceTransfer(): void
    {
        $this->mockFactoryMethod('createFilesystemProvider', function () {
            return Stub::makeEmpty(FilesystemProviderInterface::class, [
                'getFilesystemByName' => function () {
                    return Stub::make(Filesystem::class, [
                        'listContents' => function () {
                            return [['non-existent-transfer-property' => 'foo']];
                        },
                    ]);
                },
            ]);
        });
    }
}
