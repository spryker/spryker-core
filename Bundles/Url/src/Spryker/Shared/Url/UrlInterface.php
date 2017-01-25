<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Url;

/**
 * Parses and generates URLs based on URL parts. In favor of performance, URL parts are not validated.
 */
interface UrlInterface
{

    /**
     * @param array $url
     *
     * @return void
     */
    public function fromArray(array $url = []);

    /**
     * Build a URL. The generated URL will be a relative URL if a scheme or host are not provided.
     *
     * @return string
     */
    public function build();

    /**
     * @return string
     */
    public function buildEscaped();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array|string $path
     *
     * @return $this
     */
    public function setPath($path);

    /**
     * Normalize the URL so that double slashes and relative paths are removed
     *
     * @return $this
     */
    public function normalizePath();

    /**
     * Add a relative path to the currently set path
     *
     * @param array|string $relativePath
     *
     * @return $this
     */
    public function addPath($relativePath);

    /**
     * Get the path part of the URL
     *
     * @return string
     */
    public function getPath();

    /**
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme($scheme);

    /**
     * @param int $port
     *
     * @return $this
     */
    public function setPort($port);

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost($host);

    /**
     * Get the path segments of the URL as an array
     *
     * @return array
     */
    public function getPathSegments();

    /**
     * Get the query part of the URL as a QueryString object
     *
     * @return array
     */
    public function getQuery();

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addQuery($key, $value);

    /**
     * Set the query part of the URL
     *
     * @param array $query Query to set
     *
     * @return $this
     */
    public function setQuery(array $query);

}
