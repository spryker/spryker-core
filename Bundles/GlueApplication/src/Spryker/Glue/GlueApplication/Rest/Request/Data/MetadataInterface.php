<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

interface MetadataInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface|null
     */
    public function getVersion(): ?VersionInterface;

    /**
     * @return string
     */
    public function getAcceptFormat(): string;

    /**
     * @return string
     */
    public function getContentTypeFormat(): string;

    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @return bool
     */
    public function isProtected(): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute(string $key);

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setAttribute(string $key, $value): void;
}
