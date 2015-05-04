<?php

namespace SprykerFeature\Zed\Cms\Dependency\Facade;

use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface CmsToUrlInterface
{
    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource);
}
