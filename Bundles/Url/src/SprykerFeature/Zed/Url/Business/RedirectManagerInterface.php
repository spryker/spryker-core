<?php

namespace SprykerFeature\Zed\Url\Business;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlUrlTransfer;
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
     * @return UrlRedirectTransfer
     */
    public function convertRedirectEntityToTransfer(SpyRedirect $redirectEntity);

    /**
     * @param UrlRedirectTransfer $redirect
     *
     * @return UrlRedirectTransfer
     * @throws MissingUrlException
     * @throws RedirectExistsException
     */
    public function saveRedirect(UrlRedirectTransfer $redirect);

    /**
     * @param UrlRedirectTransfer $redirect
     */
    public function touchRedirectActive(UrlRedirectTransfer $redirect);

    /**
     * @param string $url
     * @param LocaleDto $locale
     * @param int $idRedirect
     *
     * @return UrlUrlTransfer
     * @throws UrlExistsException
     * @throws MissingLocaleException
     */
    public function createRedirectUrl($url, LocaleDto $locale, $idRedirect);
}
