<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Validator;

use Spryker\Shared\Kernel\Exception\ForbiddenExternalRedirectException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RedirectUrlValidator implements RedirectUrlValidatorInterface
{
    /**
     * @var string
     */
    protected const HTTP_HEADER_LOCATION = 'Location';

    /**
     * @var string
     */
    protected const RELATIVE_URL_PATTERN = '/^\/(?:[a-zA-Z0-9\p{L}\-._~%!$&\'()*+,;=:@]+(?:\/[a-zA-Z0-9\p{L}\-._~%!$&\'()*+,;=:@]*)*)?(?:\?[a-zA-Z0-9\-._~%!$&\'()*+,;=:@]*)?$/u';

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var list<string>
     */
    protected array $allowedDomains;

    /**
     * @var bool
     */
    protected bool $isStrictDomainRedirectEnabled;

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param list<string> $allowedDomains
     * @param bool $isStrictDomainRedirectEnabled
     */
    public function __construct(ValidatorInterface $validator, array $allowedDomains, bool $isStrictDomainRedirectEnabled)
    {
        $this->validator = $validator;
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

        if (!$this->isValidUrl($redirectUrl)) {
            throw new ForbiddenExternalRedirectException(sprintf('URL %s is invalid', $redirectUrl));
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

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function isValidUrl(string $url): bool
    {
        if ($this->isRelativeUrl($url)) {
            return true;
        }

        return $this->validator->validate($url, $this->createUrlConstraint())->count() === 0;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function isRelativeUrl(string $url): bool
    {
        return preg_match(static::RELATIVE_URL_PATTERN, $url) === 1;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUrlConstraint(): Constraint
    {
        return new Url([
            'relativeProtocol' => true,
        ]);
    }
}
