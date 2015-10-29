<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\RedirectExistsException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use Orm\Zed\Url\Persistence\SpyRedirect;

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
     * @return SpyRedirect
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
     * @param SpyRedirect $redirectEntity
     *
     * @return RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyRedirect $redirectEntity);

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
     * @param int $idRedirect
     *
     * @throws UrlExistsException
     * @throws MissingLocaleException
     *
     * @return UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idRedirect);

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @return UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idRedirect);

}
