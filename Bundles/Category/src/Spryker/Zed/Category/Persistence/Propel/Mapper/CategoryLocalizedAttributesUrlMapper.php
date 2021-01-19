<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryLocalizedAttributesUrlMapper
{
    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[]|\Propel\Runtime\Collection\ObjectCollection $urlEntities
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    public function mapUrlEntitiesToCategoryLocalizedAttributesTransfer(
        ObjectCollection $urlEntities,
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
    ): CategoryLocalizedAttributesTransfer {
        $urlEntity = $this->findUrlForLocale($urlEntities, $categoryLocalizedAttributesTransfer->getLocale());
        if (!$urlEntity) {
            return $categoryLocalizedAttributesTransfer;
        }

        return $categoryLocalizedAttributesTransfer->setUrl($urlEntity->getUrl());
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[]|\Propel\Runtime\Collection\ObjectCollection $urlEntities
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl|null
     */
    protected function findUrlForLocale(ObjectCollection $urlEntities, LocaleTransfer $localeTransfer): ?SpyUrl
    {
        foreach ($urlEntities as $urlEntity) {
            if ($urlEntity->getFkLocale() === $localeTransfer->getIdLocale()) {
                return $urlEntity;
            }
        }

        return null;
    }
}
