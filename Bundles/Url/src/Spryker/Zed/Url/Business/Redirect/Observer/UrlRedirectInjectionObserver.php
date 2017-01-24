<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect\Observer;

use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Spryker\Zed\Url\Business\Exception\RedirectLoopException;
use Spryker\Zed\Url\Business\Url\AbstractUrlCreatorObserver;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectInjectionObserver extends AbstractUrlCreatorObserver
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
        $this->handleRedirectInjection($urlEntity);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @throws \Spryker\Zed\Url\Business\Exception\RedirectLoopException
     *
     * @return void
     */
    protected function handleRedirectInjection(SpyUrl $urlEntity)
    {
        $newUrlRedirectEntity = $urlEntity->getSpyUrlRedirect();

        if (!$newUrlRedirectEntity) {
            return;
        }

        $finalTargetUrlRedirectEntity = $this->findUrlRedirectEntityBySourceUrl($newUrlRedirectEntity->getToUrl());

        if (!$finalTargetUrlRedirectEntity) {
            return;
        }

        if ($finalTargetUrlRedirectEntity->getToUrl() === $urlEntity->getUrl()) {
            throw new RedirectLoopException(sprintf(
                'Redirecting "%s" to "%s" resolved in a url redirect loop.',
                $urlEntity->getUrl(),
                $newUrlRedirectEntity->getToUrl()
            ));
        }

        $newUrlRedirectEntity
            ->setToUrl($finalTargetUrlRedirectEntity->getToUrl())
            ->save();

        // TODO: saving other entities should also touch them + test
    }

    /**
     * @param string $sourceUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    protected function findUrlRedirectEntityBySourceUrl($sourceUrl)
    {
        return $this->urlQueryContainer
            ->queryUrlRedirectBySourceUrl($sourceUrl)
            ->findOne();
    }

}
