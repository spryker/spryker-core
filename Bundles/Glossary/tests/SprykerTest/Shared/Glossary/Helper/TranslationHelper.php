<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Glossary\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\KeyTranslationBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class TranslationHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return int
     */
    public function haveTranslation($override = [])
    {
        $keyTranslationTransfer = (new KeyTranslationBuilder($override))->build();

        $glossaryFacade = $this->getLocator()->glossary()->facade();
        $glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);

        $identifier = $glossaryFacade->getKeyIdentifier($keyTranslationTransfer->getGlossaryKey());

        return $identifier;
    }
}
