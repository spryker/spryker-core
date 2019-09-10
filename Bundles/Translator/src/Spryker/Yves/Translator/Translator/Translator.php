<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Translator\Translator;

use InvalidArgumentException;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface;
use Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Translator implements LegacyTranslatorInterface, TranslatorInterface
{
    /**
     * @var \Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface
     */
    protected $glossaryClient;

    /**
     * @var \Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var string|null
     */
    protected $localeName;

    /**
     * @param \Spryker\Yves\Translator\Dependency\Client\TranslatorToGlossaryStorageClientInterface $glossaryClient
     * @param \Spryker\Yves\Translator\Dependency\Client\TranslatorToLocaleClientInterface $localeClient
     */
    public function __construct(TranslatorToGlossaryStorageClientInterface $glossaryClient, TranslatorToLocaleClientInterface $localeClient)
    {
        $this->glossaryClient = $glossaryClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param string $identifier The message id (may also be an object that can be cast to string)
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    public function trans(string $identifier, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        if ($locale === null) {
            $locale = $this->getLocaleName();
        }

        return $this->glossaryClient->translate($identifier, $locale, $parameters);
    }

    /**
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $identifier The message id (may also be an object that can be cast to string)
     * @param int $number The number to use to find the indice of the message
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     *
     * @return string The translated string
     */
    public function transChoice(string $identifier, int $number, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        if ($locale === null) {
            $locale = $this->getLocaleName();
        }

        $ids = explode('|', $identifier);

        if ($number === 1) {
            return $this->glossaryClient->translate($ids[0], $locale, $parameters);
        }

        if (!isset($ids[1])) {
            throw new InvalidArgumentException(sprintf('The message "%s" cannot be pluralized, because it is missing a plural (e.g. "There is one apple|There are %%count%% apples").', $identifier));
        }

        return $this->glossaryClient->translate($ids[1], $locale, $parameters);
    }

    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        if (!$this->localeName) {
            $this->localeName = $this->localeClient->getCurrentLocale();
        }

        return $this->localeName;
    }

    /**
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $localeName
     *
     * @return $this
     */
    public function setLocale(string $localeName)
    {
        $this->localeName = $localeName;

        return $this;
    }

    /**
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getLocaleName();
    }
}
