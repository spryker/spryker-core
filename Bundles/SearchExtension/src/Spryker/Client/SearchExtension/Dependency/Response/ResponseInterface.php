<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Response;

interface ResponseInterface
{
    /**
     * @return string
     */
    public function getError(): string;

    /**
     * @return array|string|null
     */
    public function getFullError();

    /**
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * @return bool
     */
    public function hasError(): bool;

    /**
     * @return bool
     */
    public function hasFailedShards(): bool;

    /**
     * @return bool
     */
    public function isOk(): bool;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getTransferInfo(): array;

    /**
     * @return float
     */
    public function getQueryTime(): float;

    /**
     * @return int
     */
    public function getEngineTime(): int;

    /**
     * @return array
     */
    public function getShardsStatistics(): array;
}
