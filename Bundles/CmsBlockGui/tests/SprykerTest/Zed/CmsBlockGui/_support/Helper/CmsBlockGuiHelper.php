<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockGui\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Silex\Application;
use Spryker\Service\Container\Container;
use Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use SprykerTest\Zed\CmsBlockGui\PageObject\CmsBlockGuiCreatePage;

class CmsBlockGuiHelper extends Module
{
    /**
     * @return void
     */
    public function _initialize(): void
    {
        if (class_exists(PropelApplicationPlugin::class)) {
            $propelApplicationPlugin = new PropelApplicationPlugin();
            $propelApplicationPlugin->provide(new Container());

            return;
        }

        $this->addBackwardCompatibleServiceProvider();
    }

    /**
     * @deprecated Will be removed in favor of {@link \Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin}.
     *
     * @return void
     */
    protected function addBackwardCompatibleServiceProvider(): void
    {
        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function _afterSuite(): void
    {
        parent::_afterSuite();

        $this->cleanUpDatabase();
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param \Exception $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail): void
    {
        parent::_failed($test, $fail);

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    private function cleanUpDatabase(): void
    {
        $this->removeCmsBlock(CmsBlockGuiCreatePage::FORM_FIELD_NAME_VALUE);
    }

    /**
     * @param string $cmsBlockName
     *
     * @return void
     */
    protected function removeCmsBlock(string $cmsBlockName): void
    {
        $cmsBlockQuery = new SpyCmsBlockQuery();
        $cmsBlockEntity = $cmsBlockQuery->findOneByName($cmsBlockName);
        if (!$cmsBlockEntity) {
            return;
        }

        $blockStoresCollection = $cmsBlockEntity->getSpyCmsBlockStores();
        if ($blockStoresCollection) {
            $blockStoresCollection->delete();
        }

        $cmsBlockEntity->delete();
    }
}
