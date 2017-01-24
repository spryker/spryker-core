<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterObserver;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectUpdateObserver extends AbstractUrlUpdaterObserver
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
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl $originalUrlEntity
     *
     * @return void
     */
    public function update(SpyUrl $urlEntity, SpyUrl $originalUrlEntity)
    {
        if ($originalUrlEntity->getUrl() === $urlEntity->getUrl()) {
            return;
        }

        $this->maintainOutdatedRedirectTargetUrls($urlEntity, $originalUrlEntity);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl $originalUrlEntity
     *
     * @return void
     */
    protected function maintainOutdatedRedirectTargetUrls(SpyUrl $urlEntity, SpyUrl $originalUrlEntity)
    {
        $chainRedirectEntities = $this->findUrlRedirectEntitiesByTargetUrl($originalUrlEntity->getUrl());

        foreach ($chainRedirectEntities as $chainRedirectEntity) {
            $chainRedirectEntity->setToUrl($urlEntity->getUrl());
            $chainRedirectEntity->save();

            // TODO: touch updated entries + test
        }
    }

    /**
     * @param string $targetUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect[]
     */
    protected function findUrlRedirectEntitiesByTargetUrl($targetUrl)
    {
        return $this->urlQueryContainer
            ->queryRedirects()
            ->findByToUrl($targetUrl);
    }

}
