<?php

namespace SprykerFeature\Zed\Url\Business;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Shared\Url\Transfer\Redirect;
use SprykerFeature\Shared\Url\Transfer\Url;
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
     * @return Redirect
     */
    public function convertRedirectEntityToTransfer(SpyRedirect $redirectEntity);

    /**
     * @param Redirect $redirect
     *
     * @return Redirect
     * @throws MissingUrlException
     * @throws RedirectExistsException
     */
    public function saveRedirect(Redirect $redirect);

    /**
     * @param Redirect $redirect
     */
    public function touchRedirectActive(Redirect $redirect);

    /**
     * @param string $url
     * @param LocaleDto $locale
     * @param int $idRedirect
     *
     * @return Url
     * @throws UrlExistsException
     * @throws MissingLocaleException
     */
    public function createRedirectUrl($url, LocaleDto $locale, $idRedirect);
}
