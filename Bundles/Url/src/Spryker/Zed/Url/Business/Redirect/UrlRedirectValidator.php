<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlRedirectValidationResponseTransfer;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

class UrlRedirectValidator implements UrlRedirectValidatorInterface
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
     * @return \Generated\Shared\Transfer\UrlRedirectValidationResponseTransfer
     */
    public function validateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $this->assertUrlRedirectTransfer($urlRedirectTransfer);

        $responseTransfer = $this->createResponseTransfer();

        if ($this->hasRedirectLoop($urlRedirectTransfer)) {
            $responseTransfer
                ->setIsValid(false)
                ->setError(sprintf(
                    'Redirecting "%s" to "%s" resolves in a URL redirect loop.',
                    $urlRedirectTransfer->getSource()->getUrl(),
                    $urlRedirectTransfer->getToUrl()
                ));
        }

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    protected function assertUrlRedirectTransfer(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $urlRedirectTransfer
            ->requireToUrl()
            ->requireSource()
            ->getSource()
                ->requireUrl();
    }

    /**
     * @return \Generated\Shared\Transfer\UrlRedirectValidationResponseTransfer
     */
    protected function createResponseTransfer()
    {
        $responseTransfer = new UrlRedirectValidationResponseTransfer();
        $responseTransfer->setIsValid(true);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return bool
     */
    protected function hasRedirectLoop(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $sourceUrl = $urlRedirectTransfer->getSource()->getUrl();
        $targetUrl = $urlRedirectTransfer->getToUrl();

        if ($sourceUrl === $targetUrl) {
            return true;
        }

        $finalTargetUrlRedirectEntity = $this->findUrlRedirectEntityBySourceUrl($targetUrl);

        return ($finalTargetUrlRedirectEntity && $finalTargetUrlRedirectEntity->getToUrl() === $sourceUrl);
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
