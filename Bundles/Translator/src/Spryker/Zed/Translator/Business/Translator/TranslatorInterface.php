<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface as SymfonyTranslatorInterface;

interface TranslatorInterface extends SymfonyTranslatorInterface, TranslatorBagInterface
{
    /**
     * @param string $format
     * @param \Symfony\Component\Translation\Loader\LoaderInterface $loader
     *
     * @return void
     */
    public function addLoader(string $format, LoaderInterface $loader): void;

    /**
     * @param string $format
     * @param mixed $resource
     * @param string $locale
     * @param string|null $domain
     *
     * @return void
     */
    public function addResource(string $format, $resource, string $locale, ?string $domain = null): void;

    /**
     * @param string $keyName
     * @param string $locale
     *
     * @return bool
     */
    public function has(string $keyName, string $locale): bool;
}
