<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Validator;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException;
use Symfony\Component\HttpFoundation\Response;

class UrlValidator implements UrlValidatorInterface
{
    protected const HTTP_HEADER_LOCATION = 'Location';

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @throws \Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException
     *
     * @return void
     */
    public function isWhitelistedRedirectDomain(Response $response): void
    {
        if (!$response->isRedirection()) {
            return;
        }

        $redirectUrl = $response->headers->get(static::HTTP_HEADER_LOCATION);

        if (parse_url($redirectUrl, PHP_URL_HOST) && !$this->isWhitelistedDomain($redirectUrl)) {
            throw new ForbiddenExternalRedirectException(sprintf('URL %s is not a part of a whitelisted domain', $redirectUrl));
        }
    }

    /**
     * @see \Spryker\Shared\Kernel\KernelConstants::STRICT_DOMAIN_REDIRECT For strict redirection check status.
     * @see \Spryker\Shared\Kernel\KernelConstants::DOMAIN_WHITELIST For allowed list of external domains.
     *
     * @param string $url
     *
     * @return bool
     */
    protected function isWhitelistedDomain(string $url): bool
    {
        $whitelistedDomains = Config::get(KernelConstants::DOMAIN_WHITELIST, []);
        $isStrictDomainRedirect = Config::get(KernelConstants::STRICT_DOMAIN_REDIRECT, false);

        if (empty($whitelistedDomains) && !$isStrictDomainRedirect) {
            return true;
        }

        $domain = parse_url($url, PHP_URL_HOST);

        foreach ($whitelistedDomains as $whitelistedDomain) {
            if ($domain === $whitelistedDomain) {
                return true;
            }
        }

        return false;
    }
}
