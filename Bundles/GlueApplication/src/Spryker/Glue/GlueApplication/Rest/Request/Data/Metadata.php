<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

class Metadata implements MetadataInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface|null
     */
    protected $version;

    /**
     * @var string
     */
    protected $acceptFormat;

    /**
     * @var string
     */
    protected $contentTypeFormat;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var bool
     */
    protected $isProtected;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param string $acceptFormat
     * @param string $contentTypeFormat
     * @param string $method
     * @param string $locale
     * @param bool $isProtected
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface|null $version
     * @param array $attributes
     */
    public function __construct(
        string $acceptFormat,
        string $contentTypeFormat,
        string $method,
        string $locale,
        bool $isProtected,
        ?VersionInterface $version = null,
        array $attributes = []
    ) {
        $this->acceptFormat = $acceptFormat;
        $this->contentTypeFormat = $contentTypeFormat;
        $this->method = $method;
        $this->locale = $locale;
        $this->isProtected = $isProtected;
        $this->attributes = $attributes;
        $this->version = $version;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface|null
     */
    public function getVersion(): ?VersionInterface
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getAcceptFormat(): string
    {
        return $this->acceptFormat;
    }

    /**
     * @return string
     */
    public function getContentTypeFormat(): string
    {
        return $this->contentTypeFormat;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return $this->isProtected;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
}
