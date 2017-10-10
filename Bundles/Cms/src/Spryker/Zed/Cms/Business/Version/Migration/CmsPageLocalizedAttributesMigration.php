<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Migration;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsPageLocalizedAttributesMigration implements MigrationInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsToLocaleInterface $localeFacade, CmsQueryContainerInterface $queryContainer)
    {
        $this->localeFacade = $localeFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $originVersionDataTransfer
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    public function migrate(CmsVersionDataTransfer $originVersionDataTransfer, CmsVersionDataTransfer $targetVersionDataTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($originVersionDataTransfer, $targetVersionDataTransfer) {
            $this->executeMigrateTransaction($originVersionDataTransfer, $targetVersionDataTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $originVersionDataTransfer
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    protected function executeMigrateTransaction(CmsVersionDataTransfer $originVersionDataTransfer, CmsVersionDataTransfer $targetVersionDataTransfer)
    {
        $this->migratePageAttributes($originVersionDataTransfer, $targetVersionDataTransfer);
        $this->migrateMetaAttributes($originVersionDataTransfer, $targetVersionDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $originVersionDataTransfer
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    protected function migratePageAttributes(CmsVersionDataTransfer $originVersionDataTransfer, CmsVersionDataTransfer $targetVersionDataTransfer)
    {
        foreach ($targetVersionDataTransfer->getCmsPage()->getPageAttributes() as $pageAttributesTransfer) {
            $cmsLocalizedAttributeEntity = $this->findOrCreatePageLocalizedAttribute(
                $originVersionDataTransfer->getCmsPage()->getFkPage(),
                $pageAttributesTransfer->getLocaleName()
            );

            $cmsLocalizedAttributeEntity->setName($pageAttributesTransfer->getName());
            $cmsLocalizedAttributeEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $originVersionDataTransfer
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    protected function migrateMetaAttributes(CmsVersionDataTransfer $originVersionDataTransfer, CmsVersionDataTransfer $targetVersionDataTransfer)
    {
        foreach ($targetVersionDataTransfer->getCmsPage()->getMetaAttributes() as $metaAttributesTransfer) {
            $cmsLocalizedAttributeEntity = $this->findOrCreatePageLocalizedAttribute(
                $originVersionDataTransfer->getCmsPage()->getFkPage(),
                $metaAttributesTransfer->getLocaleName()
            );

            $cmsLocalizedAttributeEntity->setMetaTitle($metaAttributesTransfer->getMetaTitle());
            $cmsLocalizedAttributeEntity->setMetaKeywords($metaAttributesTransfer->getMetaKeywords());
            $cmsLocalizedAttributeEntity->setMetaDescription($metaAttributesTransfer->getMetaDescription());
            $cmsLocalizedAttributeEntity->save();
        }
    }

    /**
     * @param int $idCmsPage
     * @param string $localeName
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function findOrCreatePageLocalizedAttribute($idCmsPage, $localeName)
    {
        $localeTransfer = $this->localeFacade->getLocale($localeName);

        return $this->queryContainer
            ->queryCmsPageLocalizedAttributesByFkPageAndFkLocale($idCmsPage, $localeTransfer->getIdLocale())
            ->findOneOrCreate();
    }

}
