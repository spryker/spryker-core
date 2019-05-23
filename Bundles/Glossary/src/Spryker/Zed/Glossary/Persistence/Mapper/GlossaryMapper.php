<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence\Mapper;

use Generated\Shared\Transfer\TranslationTransfer;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;

class GlossaryMapper
{
    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $glossaryTranslationEntity
     * @param \Generated\Shared\Transfer\TranslationTransfer $translationTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function mapGlossaryTranslationEntityToTranslationTransfer(SpyGlossaryTranslation $glossaryTranslationEntity, TranslationTransfer $translationTransfer): TranslationTransfer
    {
        $translationTransfer = $translationTransfer->fromArray($glossaryTranslationEntity->toArray(), true);

        return $translationTransfer;
    }
}
