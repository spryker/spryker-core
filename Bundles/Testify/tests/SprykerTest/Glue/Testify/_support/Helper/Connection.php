<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
