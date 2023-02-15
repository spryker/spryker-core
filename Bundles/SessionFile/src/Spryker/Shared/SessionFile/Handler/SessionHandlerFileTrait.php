<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Handler;

if (PHP_VERSION_ID >= 80200) {
    trait SessionHandlerFileTrait
    {
        /**
         * @param string $savePath
         * @param string $sessionName
         *
         * @return bool
         */
        public function open(string $savePath, string $sessionName): bool
        {
            return $this->executeOpen($savePath, $sessionName);
        }

        /**
         * @param string $sessionId
         *
         * @return string
         */
        public function read(string $sessionId): string
        {
            return $this->executeRead($sessionId);
        }

        /**
         * @param string $sessionId
         * @param string $sessionData
         *
         * @return bool
         */
        public function write(string $sessionId, string $sessionData): bool
        {
            return $this->executeWrite($sessionId, $sessionData);
        }

        /**
         * @param string $sessionId
         *
         * @return bool
         */
        public function destroy(string $sessionId): bool
        {
            return $this->executeDestroy($sessionId);
        }

        /**
         * @param int $maxLifetime
         *
         * @return int|false
         */
        public function gc(int $maxLifetime): int|false
        {
            return $this->executeGc($maxLifetime);
        }

        /**
         * @param string $savePath
         * @param string $sessionName
         *
         * @return bool
         */
        abstract protected function executeOpen(string $savePath, string $sessionName): bool;

        /**
         * @param string $sessionId
         *
         * @return string
         */
        abstract protected function executeRead(string $sessionId): string;

        /**
         * @param string $sessionId
         * @param string $sessionData
         *
         * @return bool
         */
        abstract protected function executeWrite(string $sessionId, string $sessionData): bool;

        /**
         * @param string $sessionId
         *
         * @return bool
         */
        abstract protected function executeDestroy(string $sessionId): bool;

        /**
         * @param int $maxLifetime
         *
         * @return int|false
         */
        abstract protected function executeGc(int $maxLifetime): int|false;
    }
} else {
    trait SessionHandlerFileTrait
    {
        /**
         * @param string $savePath
         * @param string $sessionName
         *
         * @return bool
         */
        public function open($savePath, $sessionName)
        {
            return $this->executeOpen($savePath, $sessionName);
        }

        /**
         * @param string $sessionId
         *
         * @return string
         */
        public function read(string $sessionId): string
        {
            return $this->executeRead($sessionId);
        }

        /**
         * @param string $sessionId
         * @param string $sessionData
         *
         * @return bool
         */
        public function write($sessionId, $sessionData)
        {
            return $this->executeWrite($sessionId, $sessionData);
        }

        /**
         * @param string $sessionId
         *
         * @return bool
         */
        public function destroy($sessionId)
        {
            return $this->executeDestroy($sessionId);
        }

        /**
         * @param int $maxLifetime
         *
         * @return int|false
         */
        public function gc($maxLifetime)
        {
            return $this->executeGc($maxLifetime);
        }

        /**
         * @param string $savePath
         * @param string $sessionName
         *
         * @return bool
         */
        abstract protected function executeOpen(string $savePath, string $sessionName): bool;

        /**
         * @param string $sessionId
         *
         * @return string
         */
        abstract protected function executeRead(string $sessionId): string;

        /**
         * @param string $sessionId
         * @param string $sessionData
         *
         * @return bool
         */
        abstract protected function executeWrite(string $sessionId, string $sessionData): bool;

        /**
         * @param string $sessionId
         *
         * @return bool
         */
        abstract protected function executeDestroy(string $sessionId): bool;

        /**
         * @param int $maxLifetime
         *
         * @return int|false
         */
        abstract protected function executeGc(int $maxLifetime): int|false;
    }
}
