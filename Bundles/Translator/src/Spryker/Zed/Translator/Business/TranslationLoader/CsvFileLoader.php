<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationLoader;

use Symfony\Component\Translation\Loader\CsvFileLoader as SymfonyCsvFileLoader;

class CsvFileLoader extends SymfonyCsvFileLoader implements TranslationLoaderInterface
{
    protected const LOADER_FORMAT = 'csv';

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return static::LOADER_FORMAT;
    }
}
