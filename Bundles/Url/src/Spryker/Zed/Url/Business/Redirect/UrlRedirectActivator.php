<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business\Redirect;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Spryker\Zed\Url\Dependency\UrlToTouchInterface;
use Spryker\Zed\Url\UrlConfig;

class UrlRedirectActivator implements UrlRedirectActivatorInterface
{
    /**
     * @var \Spryker\Zed\Url\Dependency\UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Url\Dependency\UrlToTouchInterface $touchFacade
     */
    public function __construct(UrlToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function activateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $idUrlRedirect = $urlRedirectTransfer
            ->requireIdUrlRedirect()
            ->getIdUrlRedirect();

        $this->touchFacade->touchActive(UrlConfig::RESOURCE_TYPE_REDIRECT, $idUrlRedirect);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deactivateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $idUrlRedirect = $urlRedirectTransfer
            ->requireIdUrlRedirect()
            ->getIdUrlRedirect();

        $this->touchFacade->touchDeleted(UrlConfig::RESOURCE_TYPE_REDIRECT, $idUrlRedirect);
    }
}
