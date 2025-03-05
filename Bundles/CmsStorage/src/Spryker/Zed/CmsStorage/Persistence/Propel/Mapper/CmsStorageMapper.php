<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SitemapUrlTransfer;
use Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage;
use Propel\Runtime\Collection\Collection;

class CmsStorageMapper
{
    /**
     * @var string
     */
    protected const COLUMN_URL = 'url';

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage> $cmsPageStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function mapCmsPageStorageEntitiesToSitemapUrlTransfers(Collection $cmsPageStorageEntities): array
    {
        $sitemapUrlTransfers = [];

        foreach ($cmsPageStorageEntities as $cmsPageStorageEntity) {
            $cmsPageStorageData = $cmsPageStorageEntity->getData();

            if (!isset($cmsPageStorageData[static::COLUMN_URL])) {
                continue;
            }

            $sitemapUrlTransfers[] = $this->mapCmsPageStorageEntityToSitemapUrlTransfer($cmsPageStorageEntity);
        }

        return $sitemapUrlTransfers;
    }

    /**
     * @param \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorage $cmsPageStorageEntity
     *
     * @return \Generated\Shared\Transfer\SitemapUrlTransfer
     */
    protected function mapCmsPageStorageEntityToSitemapUrlTransfer(SpyCmsPageStorage $cmsPageStorageEntity): SitemapUrlTransfer
    {
        return (new SitemapUrlTransfer())
            ->setUrl($cmsPageStorageEntity->getData()[static::COLUMN_URL])
            ->setUpdatedAt($cmsPageStorageEntity->getUpdatedAt(static::DATE_FORMAT))
            ->setLanguageCode($cmsPageStorageEntity->getLocale())
            ->setStoreName($cmsPageStorageEntity->getStore())
            ->setIdEntity($cmsPageStorageEntity->getFkCmsPage());
    }
}
