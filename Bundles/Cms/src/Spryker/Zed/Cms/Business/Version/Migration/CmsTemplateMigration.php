<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Migration;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsTemplateMigration implements MigrationInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface $templateManager
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $queryContainer
     */
    public function __construct(TemplateManagerInterface $templateManager, CmsQueryContainerInterface $queryContainer)
    {
        $this->templateManager = $templateManager;
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
        $this->handleDatabaseTransaction(function () use ($targetVersionDataTransfer) {
            $this->executeMigrateTransaction($targetVersionDataTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    protected function executeMigrateTransaction(CmsVersionDataTransfer $targetVersionDataTransfer)
    {
        $templatePath = $targetVersionDataTransfer->getCmsTemplate()->getTemplatePath();
        $templateName = $targetVersionDataTransfer->getCmsTemplate()->getTemplateName();
        $idCmsTemplate = $targetVersionDataTransfer->getCmsTemplate()->getIdCmsTemplate();

        if (!$this->templateManager->hasTemplatePath($templatePath)) {
            $cmsTemplateTransfer = $this->templateManager->createTemplate($templateName, $templatePath);
            $idCmsTemplate = $cmsTemplateTransfer->getIdCmsTemplate();
        }

        $this->templateManager->checkTemplateFileExists($templatePath);
        $cmsPageEntity = $this->queryContainer->queryPageById($targetVersionDataTransfer->getCmsPage()
            ->getFkPage())
            ->findOne();
        $cmsPageEntity->setFkTemplate($idCmsTemplate);
        $cmsPageEntity->save();
    }
}
