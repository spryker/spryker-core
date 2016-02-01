<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Exception\RedirectExistsException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;

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
     * @param string $toUrl
     * @param int $status
     *
     * @return redirectTransfer
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
