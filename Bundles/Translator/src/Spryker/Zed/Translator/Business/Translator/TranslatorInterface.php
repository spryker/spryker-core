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
    public function addLoader($format, LoaderInterface $loader);

    /**
     * @param string $format
     * @param mixed $resource
     * @param string $locale
     * @param string|null $domain
     *
     * @return void
     */
    public function addResource($format, $resource, $locale, $domain = null);

    /**
     * @param string $keyName
     * @param string $locale
     *
     * @return bool
     */
    public function has(string $keyName, string $locale): bool;
}
