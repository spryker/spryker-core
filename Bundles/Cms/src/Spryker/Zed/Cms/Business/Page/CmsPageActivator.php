<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\CannotActivatePageException;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Throwable;

class CmsPageActivator implements CmsPageActivatorInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Cms\Communication\Plugin\PostCmsPageActivatorPluginInterface[]
     */
    protected $postCmsPageActivatorPlugins;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\Cms\Communication\Plugin\PostCmsPageActivatorPluginInterface[] $postCmsPageActivatorPlugins
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer, CmsToTouchFacadeInterface $touchFacade, array $postCmsPageActivatorPlugins)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->postCmsPageActivatorPlugins = $postCmsPageActivatorPlugins;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function activate(int $idCmsPage): void
    {
        $cmsPageEntity = $this->getCmsPageEntity($idCmsPage);

        $this->assertCanActivatePage($idCmsPage);

        try {
            $this->cmsQueryContainer->getConnection()->beginTransaction();

            $cmsPageEntity->setIsActive(true);
            $cmsPageEntity->save();

            $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $cmsPageEntity->getIdCmsPage());

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Throwable $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        $this->runPostActivatorPlugins($this->generateCmsPageTransferFromEntity($cmsPageEntity));
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\CannotActivatePageException
     *
     * @return bool
     */
    protected function assertCanActivatePage(int $idCmsPage): bool
    {
        if ($this->countNumberOfGlossaryKeysForIdCmsPage($idCmsPage) === 0) {
            throw new CannotActivatePageException(
                sprintf('Cannot activate CMS page, page placeholders not provided!')
            );
        }

        return true;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function deactivate(int $idCmsPage): void
    {
        $cmsPageEntity = $this->getCmsPageEntity($idCmsPage);

        try {
            $this->cmsQueryContainer->getConnection()->beginTransaction();

            $cmsPageEntity->setIsActive(false);
            $cmsPageEntity->save();

            $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $cmsPageEntity->getIdCmsPage());

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Throwable $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        $this->runPostActivatorPlugins($this->generateCmsPageTransferFromEntity($cmsPageEntity));
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function getCmsPageEntity(int $idCmsPage): SpyCmsPage
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($idCmsPage)
            ->findOne();

        if ($cmsPageEntity === null) {
            throw new MissingPageException(
                sprintf(
                    'CMS page with id "%d" not found.',
                    $idCmsPage
                )
            );
        }

        return $cmsPageEntity;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function generateCmsPageTransferFromEntity(SpyCmsPage $cmsPageEntity): CmsPageTransfer
    {
        $cmsPageTransfer = (new CmsPageTransfer())->fromArray($cmsPageEntity->toArray(), true);
        $cmsPageTransfer->setFkPage($cmsPageEntity->getIdCmsPage());

        return $cmsPageTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return int
     */
    protected function countNumberOfGlossaryKeysForIdCmsPage(int $idCmsPage): int
    {
        return $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($idCmsPage)->count();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return void
     */
    protected function runPostActivatorPlugins(CmsPageTransfer $cmsPageTransfer): void
    {
        foreach ($this->postCmsPageActivatorPlugins as $postCmsPageActivatorPlugin) {
            $postCmsPageActivatorPlugin->execute($cmsPageTransfer);
        }
    }
}
