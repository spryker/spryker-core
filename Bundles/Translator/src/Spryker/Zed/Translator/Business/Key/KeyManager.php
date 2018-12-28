<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Key;

use Spryker\Zed\Translator\Business\Translator\TranslatorInterface;

class KeyManager implements KeyManagerInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    protected $translator;

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        $locale = $this->translator->getLocale();
        $catalogue = $this->translator->getCatalogue($locale);

        return $catalogue->defines($keyName);
    }
}
