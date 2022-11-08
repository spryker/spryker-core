<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionFile;

use Codeception\Actor;
use Generated\Shared\Transfer\SessionEntityRequestTransfer;

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
 */
class SessionFileSharedTester extends Actor
{
    use _generated\SessionFileSharedTesterActions;

    /**
     * @var string
     */
    protected const ACTIVE_SESSION_FILE_PATH = __DIR__ . '/../../../../_output';

    /**
     * @return string
     */
    public function getActiveSessionFilePath(): string
    {
        return static::ACTIVE_SESSION_FILE_PATH;
    }

    /**
     * @param string $filePath
     *
     * @return void
     */
    public function clearSessionIfExists(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return string
     */
    public function getSessionFilePath(SessionEntityRequestTransfer $sessionEntityRequestTransfer): string
    {
        return static::ACTIVE_SESSION_FILE_PATH .
            sprintf(
                '/session:%s:%s',
                $sessionEntityRequestTransfer->getEntityType(),
                $sessionEntityRequestTransfer->getIdEntity(),
            );
    }
}
