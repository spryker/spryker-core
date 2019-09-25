<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Url;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Url\UrlFactory getFactory()
 */
class UrlClient extends AbstractClient implements UrlClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $url
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\UrlCollectorStorageTransfer|bool
     */
    public function findUrl($url, $localeName)
    {
        return $this->getFactory()->createUrlMatcher()->findUrl($url, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
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
