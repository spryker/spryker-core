<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Collector;

use Spryker\Client\Collector\Matcher\UrlMatcherInterface;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @todo Rename all YvesExport Bundles to PageExport or just Export.
 *
 * @method \Spryker\Client\Collector\CollectorFactory getFactory()
 */
class CollectorClient extends AbstractClient implements UrlMatcherInterface, CollectorClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use \Spryker\Client\Url\UrlClient::matchUrl() instead.
     *
     * @param string $url
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName)
    {
        return $this->getFactory()->createUrlMatcher()->matchUrl($url, $localeName);
    }
}
