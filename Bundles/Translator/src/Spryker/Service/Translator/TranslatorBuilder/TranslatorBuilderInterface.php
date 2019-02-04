<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslatorBuilder;

use Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface;

interface TranslatorBuilderInterface
{
    /**
     * @param \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface $translator
     *
     * @return \Spryker\Service\Translator\Translator\TranslatorResourceAwareInterface
     */
    public function buildTranslator(TranslatorResourceAwareInterface $translator): TranslatorResourceAwareInterface;
}
