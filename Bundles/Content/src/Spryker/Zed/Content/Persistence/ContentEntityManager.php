<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Persistence;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Content\Persistence\ContentPersistenceFactory getFactory()
 */
class ContentEntityManager extends AbstractEntityManager implements ContentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function saveContent(ContentTransfer $contentTransfer): ContentTransfer
    {
        $spyContentEntity = $this->getFactory()
            ->createContentQuery()
            ->filterByIdContent($contentTransfer->getIdContent())
            ->findOneOrCreate();

        $spyContentEntity->fromArray($contentTransfer->toArray());

        foreach ($contentTransfer->getLocalizedContents() as $localizedContent) {
            $contentLocalizedEntity = $this->getFactory()
                ->createContentLocalizedQuery()
                ->filterByFkContent($contentTransfer->getIdContent())
                ->filterByFkLocale($localizedContent->getFkLocale())
                ->findOneOrCreate();
            $contentLocalizedEntity->fromArray($localizedContent->toArray());

            $spyContentEntity->addSpyContentLocalized($contentLocalizedEntity);
        }
        $spyContentEntity->save();

        return $this->getFactory()->createContentMapper()->mapContentEntityToTransfer($spyContentEntity);
    }
}
