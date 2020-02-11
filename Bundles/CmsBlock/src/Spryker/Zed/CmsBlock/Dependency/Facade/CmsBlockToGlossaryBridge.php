<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Dependency\Facade;

use Generated\Shared\Transfer\KeyTranslationTransfer;

class CmsBlockToGlossaryBridge implements CmsBlockToGlossaryInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface $glossaryFacade
     */
    public function __construct($glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param array $idKeys
     *
     * @return void
     */
    public function deleteTranslationsByFkKeys(array $idKeys)
    {
        $this->glossaryFacade->deleteTranslationsByFkKeys($idKeys);
    }

    /**
     * @param array $idKeys
     *
     * @return bool
     */
    public function deleteKeys(array $idKeys)
    {
        return $this->glossaryFacade->deleteKeys($idKeys);
    }

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        return $this->glossaryFacade->hasKey($keyName);
    }

    /**
     * @param \Generated\Shared\Transfer\KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer)
    {
        return $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
    }
}
