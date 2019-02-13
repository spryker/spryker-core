<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Persistence;

use DateTime;
use Generated\Shared\Transfer\ContentTransfer;
use Orm\Zed\Content\Persistence\SpyContent;
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

        $this->extractLocalizedContents($contentTransfer, $spyContentEntity);

        $spyContentEntity->save();

        return $this->getFactory()->createContentMapper()->mapContentEntityToTransfer($spyContentEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     * @param \Orm\Zed\Content\Persistence\SpyContent $spyContentEntity
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    protected function extractLocalizedContents(ContentTransfer $contentTransfer, SpyContent $spyContentEntity): SpyContent
    {
        $isModified = false;

        foreach ($contentTransfer->getLocalizedContents() as $localizedContent) {
            $contentLocalizedEntity = $this->getFactory()
                ->createContentLocalizedQuery()
                ->filterByFkContent($contentTransfer->getIdContent())
                ->filterByFkLocale($localizedContent->getFkLocale())
                ->findOneOrCreate();

            if (!$localizedContent->getParameters()) {
                $isModified = true;
                $spyContentEntity->removeSpyContentLocalized($contentLocalizedEntity);

                continue;
            }
            $contentLocalizedEntity->fromArray($localizedContent->toArray());
            if ($contentLocalizedEntity->isModified()) {
                $isModified = true;
            }
            $spyContentEntity->addSpyContentLocalized($contentLocalizedEntity);
        }

        if ($isModified) {
            $spyContentEntity->setUpdatedAt(new DateTime());
        }

        return $spyContentEntity;
    }
}
