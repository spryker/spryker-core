<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationResource;

use Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface;

interface TranslationResourceFileLoaderInterface
{
    /**
     * @return string|null
     */
    public function getDomain(): ?string;

    /**
     * @param string $filename
     *
     * @return string|null
     */
    public function findLocaleFromFilename(string $filename): ?string;

    /**
     * @return \Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface
     */
    public function getLoader(): TranslationLoaderInterface;

    /**
     * @return string[]
     */
    public function getFilePaths(): array;
}
