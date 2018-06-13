<?php

namespace Spryker\Yves\Url\Validator;

use Spryker\Yves\Kernel\Controller\UrlValidator\ExternalUrlValidatorStackInterface;
use Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException;

class ExternalRedirectUrlValidatorStack implements ExternalUrlValidatorStackInterface
{
    protected $externalUrlValidators;

    public function __construct(array $externalUrlValidators)
    {
        $this->externalUrlValidators = $externalUrlValidators;
    }

    public function execute(string $url): void
    {
        //Do not validate if url does not contain host.
        if (strpos($url, '/') === 0) {
            return;
        }

        array_walk($this->externalUrlValidators, function ($plugin) use ($url) {
            if (!$plugin->execute($url)) {
                throw new ForbiddenExternalRedirectException("Url is not in a whitelist: $url");
            }
        });
    }
}
