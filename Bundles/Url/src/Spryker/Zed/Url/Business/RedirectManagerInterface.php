<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;

/**
 * @deprecated Use business interfaces from Spryker\Zed\Url\Business\Redirect namespace.
 */
interface RedirectManagerInterface
{

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirect
     */
    public function createRedirect($toUrl, $status = 301);

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect(RedirectTransfer $redirectTransfer);

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 301);

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlRedirect $redirectEntity
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyUrlRedirect $redirectEntity);

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Spryker\Zed\Url\Business\Exception\RedirectExistsException
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect);

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect);

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect);

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect);

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idUrlRedirect);

}
