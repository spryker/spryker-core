<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslatorBuilder;

use Spryker\Zed\Translator\Business\Translator\TranslatorInterface;

interface TranslatorBuilderInterface
{
    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface $translator
     *
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    public function buildTranslator(TranslatorInterface $translator): TranslatorInterface;
}
