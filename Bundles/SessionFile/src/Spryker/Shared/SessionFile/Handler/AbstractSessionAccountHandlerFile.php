<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Handler;

use Spryker\Shared\SessionFile\Hasher\HasherInterface;

abstract class AbstractSessionAccountHandlerFile implements SessionAccountHandlerFileInterface
{
    /**
     * @var \Spryker\Shared\SessionFile\Hasher\HasherInterface
     */
    protected $hasher;

    /**
     * @param \Spryker\Shared\SessionFile\Hasher\HasherInterface $hasher
     */
    public function __construct(HasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param int $idAccount
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionAccount(int $idAccount, string $idSession): void
    {
        file_put_contents(
            $this->buildFileName($idAccount),
            $this->hasher->encrypt($idSession),
        );
    }

    /**
     * @param int $idAccount
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionAccountValid(int $idAccount, string $idSession): bool
    {
        $fileName = $this->buildFileName($idAccount);

        if (!file_exists($fileName)) {
            return true;
        }

        $hash = file_get_contents($fileName);

        if ($hash === false) {
            return true;
        }

        return $this->hasher->validate(
            $idSession,
            $hash,
        );
    }

    /**
     * @param int $idAccount
     *
     * @return string
     */
    protected function buildFileName(int $idAccount): string
    {
        if (!is_dir($this->getActiveSessionFilePath())) {
            mkdir($this->getActiveSessionFilePath(), 0775, true);
        }

        return rtrim($this->getActiveSessionFilePath(), DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            sprintf(
                'session:%s:%s',
                $this->getAccountType(),
                $idAccount,
            );
    }

    /**
     * @return string
     */
    abstract protected function getAccountType(): string;

    /**
     * @return string
     */
    abstract protected function getActiveSessionFilePath(): string;
}
