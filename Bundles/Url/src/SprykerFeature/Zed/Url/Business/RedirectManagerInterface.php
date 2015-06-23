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
use SprykerFeature\Zed\Url\Persistence\Propel\SpyRedirect;

interface RedirectManagerInterface
{
    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return SpyRedirect
     * @throws MissingUrlException
     * @throws \Exception
     * @throws PropelException
     */
    public function createRedirect($toUrl, $status = 301);

    /**
     * @param SpyRedirect $redirectEntity
     *
     * @return RedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyRedirect $redirectEntity);

    /**
     * @param RedirectTransfer $redirect
     *
     * @return RedirectTransfer
     * @throws MissingUrlException
     * @throws RedirectExistsException
     */
    public function saveRedirect(RedirectTransfer $redirect);

    /**
     * @param RedirectTransfer $redirect
     */
    public function touchRedirectActive(RedirectTransfer $redirect);

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @return UrlTransfer
     * @throws UrlExistsException
     * @throws MissingLocaleException
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idRedirect);
}
