<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Url\UrlServiceFactory getFactory()
 */
class UrlService extends AbstractService implements UrlServiceInterface
{

    /**
     * {@inheritdoc}
     *
     * @param string $url Full URL used to create a Url object
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    public function parse($url)
    {
        return $this->getFactory()
            ->createUrlParser()
            ->parse($url);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $url
     * @param array $query
     * @param array $options
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    public function generate($url, array $query = [], array $options = [])
    {
        return $this->getFactory()
            ->createUrlGenerator()
            ->generate($url, $query, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $bundle
     * @param string|null $controller
     * @param string|null $action
     * @param array $queryParameter
     *
     * @return string
     */
    public function build($bundle, $controller = null, $action = null, array $queryParameter = [])
    {
        return $this->getFactory()
            ->createUrlBuilder()
            ->build($bundle, $controller, $action, $queryParameter);
    }

}
