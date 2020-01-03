<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GlossaryKeyTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;

class GlossaryMapper
{
    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $glossaryTranslationEntity
     * @param \Generated\Shared\Transfer\TranslationTransfer $translationTransfer
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function mapGlossaryTranslationEntityToTranslationTransfer(
        SpyGlossaryTranslation $glossaryTranslationEntity,
        TranslationTransfer $translationTransfer
    ): TranslationTransfer {
        return $translationTransfer->fromArray($glossaryTranslationEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryKey $glossaryKeyEntity
     * @param \Generated\Shared\Transfer\GlossaryKeyTransfer $glossaryKeyTransfer
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer
     */
    public function mapGlossaryKeyEntityToGlossaryKeyTransfer(
        SpyGlossaryKey $glossaryKeyEntity,
        GlossaryKeyTransfer $glossaryKeyTransfer
    ): GlossaryKeyTransfer {
        return $glossaryKeyTransfer->fromArray($glossaryKeyEntity->toArray(), true);
    }
}
