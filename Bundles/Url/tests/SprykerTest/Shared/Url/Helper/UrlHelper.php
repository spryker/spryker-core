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
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class UrlHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function haveUrl(): UrlTransfer
    {
        $urlFacade = $this->getUrlFacade();

        $urlTransfer = $this->createUrlTransfer();

        $existingUrlTransfer = $urlFacade->findUrl($urlTransfer);
        if ($existingUrlTransfer) {
            return $existingUrlTransfer;
        }

        return $urlFacade->createUrl($urlTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function haveUrlRedirect(): UrlRedirectTransfer
    {
        $urlFacade = $this->getUrlFacade();

        $urlRedirectTransfer = (new UrlRedirectBuilder())
            ->build()
            ->setSource($this->createUrlTransfer())
            ->setStatus(301);

        return $urlFacade->createUrlRedirect($urlRedirectTransfer);
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

    /**
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(): UrlTransfer
    {
        $locale = $this->getLocaleFacade()->getCurrentLocale();
        return (new UrlBuilder())->build()->setFkLocale($locale->getIdLocale());
    }
}
