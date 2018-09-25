<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Migration;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsGlossaryKeyMappingMigration implements MigrationInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface
     */
    protected $cmsGlossarySaver;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface $cmsGlossarySaver
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsGlossarySaverInterface $cmsGlossarySaver, CmsToLocaleInterface $localeFacade, CmsQueryContainerInterface $queryContainer)
    {
        $this->cmsGlossarySaver = $cmsGlossarySaver;
        $this->localeFacade = $localeFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $originVersionDataTransfer
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    public function migrate(CmsVersionDataTransfer $originVersionDataTransfer, CmsVersionDataTransfer $targetVersionDataTransfer): void
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
    protected function executeMigrateTransaction(CmsVersionDataTransfer $originVersionDataTransfer, CmsVersionDataTransfer $targetVersionDataTransfer): void
    {
        $this->cmsGlossarySaver->deleteCmsGlossary($originVersionDataTransfer->getCmsPage()->getFkPage());
        $this->cmsGlossarySaver->saveCmsGlossary($targetVersionDataTransfer->getCmsGlossary());
    }
}
