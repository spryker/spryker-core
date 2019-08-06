<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

interface Connection
{
    /**
     * @return string
     */
    public function getRequestUrl(): string;

    /**
     * @return string
     */
    public function getRequestMethod(): string;

    /**
     * @return array
     */
    public function getRequestParameters(): array;

    /**
     * @return array
     */
    public function getRequestFiles(): array;

    /**
     * @return string
     */
    public function getResponseBody(): string;

    /**
     * @return int
     */
    public function getResponseCode(): int;

    /**
     * @return string
     */
    public function getResponseContentType(): string;
}
