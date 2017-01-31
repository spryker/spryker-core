<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;

class CmsToUrlBridge implements CmsToUrlInterface
{

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Url\Business\UrlFacadeInterface $urlFacade
     */
    public function __construct($urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl(UrlTransfer $urlTransfer)
    {
        return $this->urlFacade->createUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updateUrl(UrlTransfer $urlTransfer)
    {
        return $this->urlFacade->updateUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function activateUrl(UrlTransfer $urlTransfer)
    {
        $this->urlFacade->activateUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deactivateUrl(UrlTransfer $urlTransfer)
    {
        $this->urlFacade->deactivateUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrl(UrlTransfer $urlTransfer)
    {
        return $this->urlFacade->hasUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $redirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect(UrlRedirectTransfer $redirectTransfer)
    {
        $this->urlFacade->deleteUrlRedirect($redirectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function createUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->urlFacade->createUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function updateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->urlFacade->updateUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectValidationResponseTransfer
     */
    public function validateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->urlFacade->validateUrlRedirect($urlRedirectTransfer);
    }

}
