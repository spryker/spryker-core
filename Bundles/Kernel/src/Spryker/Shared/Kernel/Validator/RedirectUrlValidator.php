<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Validator;

use Spryker\Shared\Kernel\Exception\ForbiddenExternalRedirectException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RedirectUrlValidator implements RedirectUrlValidatorInterface
{
    /**
     * @var string
     */
    protected const HTTP_HEADER_LOCATION = 'Location';

    /**
     * @var array<string>
     */
    protected $allowedDomains;

    /**
     * @var bool
     */
    protected $isStrictDomainRedirectEnabled;

    /**
     * @param array<string> $allowedDomains
     * @param bool $isStrictDomainRedirectEnabled
     */
    public function __construct(array $allowedDomains, bool $isStrictDomainRedirectEnabled)
    {
        $this->allowedDomains = $allowedDomains;
        $this->isStrictDomainRedirectEnabled = $isStrictDomainRedirectEnabled;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @throws \Spryker\Shared\Kernel\Exception\ForbiddenExternalRedirectException
     *
     * @return void
     */
    public function validateRedirectUrl(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if (!$response->isRedirection()) {
            return;
        }

        $redirectUrl = $response->headers->get(static::HTTP_HEADER_LOCATION);
        if (!$redirectUrl) {
            return;
        }

        $domain = (string)parse_url($redirectUrl, PHP_URL_HOST);
        if (!$this->isAllowedDomain($domain, $event->getRequest())) {
            throw new ForbiddenExternalRedirectException(sprintf('URL %s is not an allowed domain', $redirectUrl));
        }
    }

    /**
     * @see \Spryker\Shared\Kernel\KernelConstants::STRICT_DOMAIN_REDIRECT For strict redirection check status.
     * @see \Spryker\Shared\Kernel\KernelConstants::DOMAIN_WHITELIST For allowed list of external domains.
     *
     * @param string $domain
     * @param \Symfony\Component\HttpFoundation\Request $currentRequest
     *
     * @return bool
     */
    protected function isAllowedDomain(string $domain, Request $currentRequest): bool
    {
        if (!$domain || $domain === $currentRequest->getHost() || !$this->isStrictDomainRedirectEnabled) {
            return true;
        }

        return in_array($domain, $this->allowedDomains, true);
    }
}
