<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationLoader;

use Symfony\Component\Translation\Loader\LoaderInterface;

interface TranslationLoaderInterface extends LoaderInterface
{
    /**
     * @return string
     */
    public function getFormat(): string;
}
