<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Finder;

interface TranslationFinderInterface
{
    /**
     * @return array
     */
    public function getTranslationFiles(): array;

    /**
     * @return string
     */
    public function getFileFormat(): string;
}
