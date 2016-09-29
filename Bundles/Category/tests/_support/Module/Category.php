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

class Category extends Module
{

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
        foreach (CategoryCreatePage::CATEGORIES as $categoryKey) {
            $this->removeCategory($categoryKey);
        }
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
        $categoryEntity->getAttributes()->delete();
        $categoryEntity->getNodes()->delete();
        $categoryEntity->delete();
    }


}
