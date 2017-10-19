<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectReader implements UrlRedirectReaderInterface
{
    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     */
    public function __construct(UrlQueryContainerInterface $urlQueryContainer)
    {
        $this->urlQueryContainer = $urlQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer|null
     */
    public function findUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectEntity = $this->queryUrlRedirectEntity($urlRedirectTransfer)->findOne();

        if (!$urlRedirectEntity) {
            return null;
        }

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->fromArray($urlRedirectEntity->toArray(), true);

        return $urlRedirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return bool
     */
    public function hasUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectCount = $this->queryUrlRedirectEntity($urlRedirectTransfer)->count();

        return $urlRedirectCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    protected function queryUrlRedirectEntity(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectTransfer->requireIdUrlRedirect();

        $urlRedirectQuery = $this->urlQueryContainer->queryRedirectById($urlRedirectTransfer->getIdUrlRedirect());

        return $urlRedirectQuery;
    }
}
