<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\Translator;

use Symfony\Component\Translation\Loader\LoaderInterface;

interface TranslatorResourceAwareInterface
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
}
