<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationLoader;

use Symfony\Component\Translation\Loader\XliffFileLoader as SymfonyXliffFileLoader;

class XliffLoader extends SymfonyXliffFileLoader implements TranslationLoaderInterface
{
    protected const LOADER_FORMAT = 'xlf';

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return static::LOADER_FORMAT;
    }
}
