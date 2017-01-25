<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url;

/**
 * @method \Spryker\Service\Url\UrlServiceFactory getFactory()
 */
interface UrlServiceInterface
{

    /**
     * Specification:
     * - Factory method to create a new URL from a complete URL string
     *
     * @param string $url Full URL used to create a Url object
     *
     * @return \Spryker\Shared\Url\Url
     */
    public function parse($url);

    /**
     * Specification:
     * - Factory method to create an internal URL from a path string
     *
     * @param string $url
     * @param array $query
     * @param array $options
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    public function generate($url, array $query = [], array $options = []);

    /**
     * Specification:
     * - TODO: add specification
     *
     * @param string $bundle
     * @param string|null $controller
     * @param string|null $action
     * @param array $queryParameter
     *
     * @return string
     */
    public function build($bundle, $controller = null, $action = null, array $queryParameter = []);

}
