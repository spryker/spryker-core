<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectAppendObserver extends AbstractUrlCreatorObserver
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
     *
     * @return void
     */
    public function update(SpyUrl $urlEntity)
    {
        $this->handleRedirectAppend($urlEntity);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return void
     */
    protected function handleRedirectAppend(SpyUrl $urlEntity)
    {
        $redirectEntity = $urlEntity->getSpyUrlRedirect();

        if (!$redirectEntity) {
            return;
        }

        $chainRedirectEntities = $this->findUrlRedirectEntitiesByTargetUrl($urlEntity->getUrl());

        foreach ($chainRedirectEntities as $chainRedirectEntity) {
            $chainRedirectEntity->setToUrl($redirectEntity->getToUrl());
            $chainRedirectEntity->save();

            // TODO: saving other entities should also touch them when saveAndTouch was called. + test
        }
    }

    /**
     * @param string $targetUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect[]
     */
    protected function findUrlRedirectEntitiesByTargetUrl($targetUrl)
    {
        $chainRedirectEntities = $this->urlQueryContainer
            ->queryRedirects()
            ->findByToUrl($targetUrl);

        return $chainRedirectEntities;
    }

}
