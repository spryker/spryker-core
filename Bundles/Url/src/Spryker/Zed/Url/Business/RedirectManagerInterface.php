<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
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
     * @throws MissingUrlException
     * @throws \Exception
     * @throws PropelException
     *
     * @return SpyUrlRedirect
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
     * @param SpyUrlRedirect $redirectEntity
     *
     * @return RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyUrlRedirect $redirectEntity);

    /**
     * @param RedirectTransfer $redirect
     *
     * @throws MissingUrlException
     * @throws RedirectExistsException
     *
     * @return RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect);

    /**
     * @param RedirectTransfer $redirect
     *
     * @return RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect);

    /**
     * @param RedirectTransfer $redirect
     */
    public function touchRedirectActive(RedirectTransfer $redirect);

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @throws UrlExistsException
     * @throws MissingLocaleException
     *
     * @return UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect);

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idUrlRedirect);

}
