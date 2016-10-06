<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Category\Module;

use Acceptance\Category\Category\Zed\PageObject\CategoryCreatePage;
use Codeception\Module;
use Codeception\Step;
use Codeception\TestCase;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Silex\Application;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

class Category extends Module
{

    /**
     * @param null $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);

        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @param \Codeception\Step $step
     *
     * @return void
     */
    public function _afterStep(Step $step)
    {
        parent::_afterStep($step);

        $this->cleanUpDatabase();
    }

    /**
     * @param \Codeception\TestCase $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestCase $test, $fail)
    {
        parent::_failed($test, $fail);

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    private function cleanUpDatabase()
    {
        $this->removeCategory(CategoryCreatePage::CATEGORY_A);
        $this->removeCategory(CategoryCreatePage::CATEGORY_B);
    }

    /**
     * @param string $categoryKey
     *
     * @return void
     */
    protected function removeCategory($categoryKey)
    {
        $categoryQuery = new SpyCategoryQuery();
        $categoryEntity = $categoryQuery->findOneByCategoryKey($categoryKey);
        if (!$categoryEntity) {
            return;
        }
        $attributeEntityCollection = $categoryEntity->getAttributes();
        if ($attributeEntityCollection) {
            $attributeEntityCollection->delete();
        }

        $nodeEntityCollection = $categoryEntity->getNodes();
        if ($nodeEntityCollection) {
            foreach ($nodeEntityCollection as $nodeEntity) {
                $closureTableEntries = $nodeEntity->getClosureTables();
                if ($closureTableEntries) {
                    $closureTableEntries->delete();
                }
            }
            $nodeEntityCollection->delete();
        }

        $categoryEntity->delete();
    }

    /**
     * @param string $categoryKey
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    public function loadCategoryByCategoryKey($categoryKey)
    {
        $categoryQuery = new SpyCategoryQuery();

        return $categoryQuery->findOneByCategoryKey($categoryKey);
    }
}
