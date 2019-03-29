<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Url\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\UrlBuilder;
use Generated\Shared\DataBuilder\UrlRedirectBuilder;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Url\Business\UrlFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class UrlDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $urlOverride
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function haveUrl(array $urlOverride = []): UrlTransfer
    {
        $urlTransfer = $this->buildUrl($urlOverride);

        $this->getUrlFacade()->createUrl($urlTransfer);

        $this->debug(sprintf(
            'Inserted URL: %d',
            $urlTransfer->getIdUrl()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($urlTransfer) {
            $this->cleanupUrl($urlTransfer);
        });

        return $urlTransfer;
    }

    /**
     * @param array $urlRedirectOverride
     * @param array $urlOverride
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function haveUrlRedirect(array $urlRedirectOverride = [], array $urlOverride = []): UrlRedirectTransfer
    {
        $urlRedirectTransfer = (new UrlRedirectBuilder())
            ->seed($urlRedirectOverride)
            ->build();

        if ($urlRedirectTransfer->getSource() === null) {
            $urlTransfer = $this->buildUrl($urlOverride);

            $urlRedirectTransfer->setSource($urlTransfer);
        }

        $this->getUrlFacade()->createUrlRedirect($urlRedirectTransfer);

        $this->debug(sprintf(
            'Inserted URL Redirect: %d',
            $urlRedirectTransfer->getIdUrlRedirect()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($urlRedirectTransfer) {
            $this->cleanupUrlRedirect($urlRedirectTransfer);
        });

        return $urlRedirectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function cleanupUrl(UrlTransfer $urlTransfer): void
    {
        $this->debug(sprintf('Deleting URL: %d', $urlTransfer->getIdUrl()));

        $this->getUrlFacade()->deleteUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    protected function cleanupUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer): void
    {
        $this->debug(sprintf('Deleting URL Redirect: %d', $urlRedirectTransfer->getIdUrlRedirect()));

        $this->getUrlFacade()->deleteUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @param array $urlOverride
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function buildUrl(array $urlOverride = []): UrlTransfer
    {
        $urlTransfer = (new UrlBuilder())
            ->seed($urlOverride)
            ->build();

        if ($urlTransfer->getFkLocale() === null) {
            $currentLocaleTransfer = $this->getLocaleFacade()->getCurrentLocale();

            $urlTransfer->setFkLocale($currentLocaleTransfer->getIdLocale());
        }

        return $urlTransfer;
    }

    /**
     * @return \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected function getUrlFacade(): UrlFacadeInterface
    {
        return $this->getLocator()->url()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
