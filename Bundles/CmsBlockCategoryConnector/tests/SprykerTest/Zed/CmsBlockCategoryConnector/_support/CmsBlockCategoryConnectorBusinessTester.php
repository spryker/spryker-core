<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockCategoryConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface;
use Spryker\Zed\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsBlockCategoryConnectorBusinessTester extends Actor
{
    use _generated\CmsBlockCategoryConnectorBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param array $cmsBlockData
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function haveCmsBlockWithCategory(CategoryTransfer $categoryTransfer, array $cmsBlockData = []): CmsBlockTransfer
    {
        $cmsCategoryFacade = $this->getCmsBlockCategoryConnectorFacade();

        $cmsCategoryFacade->syncCmsBlockCategoryPosition();

        $cmsBlockCategoryPositionTransfer = $cmsCategoryFacade->findCmsBlockCategoryPositionByName($this->getDefaultPositionName());

        $cmsBlockTransfer = $this->haveCmsBlock($cmsBlockData);
        $cmsBlockTransfer->setFkTemplate($categoryTransfer->getFkCategoryTemplate());
        $cmsBlockTransfer->setIdCategories([
            $cmsBlockCategoryPositionTransfer->getIdCmsBlockCategoryPosition() => [$categoryTransfer->getIdCategory()],
        ]);

        $cmsCategoryFacade->updateCmsBlockCategoryRelations($cmsBlockTransfer);

        return $cmsBlockTransfer;
    }

    /**
     * @return \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface
     */
    public function getCmsBlockCategoryConnectorFacade(): CmsBlockCategoryConnectorFacadeInterface
    {
        return $this->getLocator()->cmsBlockCategoryConnector()->facade();
    }

    /**
     * @return string
     */
    public function getDefaultPositionName(): string
    {
        return (new CmsBlockCategoryConnectorConfig())->getCmsBlockCategoryPositionDefault();
    }
}
