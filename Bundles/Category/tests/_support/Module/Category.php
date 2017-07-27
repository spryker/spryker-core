<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Category\Module;

use Acceptance\Category\Category\Zed\PageObject\CategoryCreatePage;
use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Silex\Application;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

class Category extends Module
{

    /**
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);

        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        parent::_afterSuite();

        $this->cleanUpDatabase();
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail)
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
                $closureTableEntries = $nodeEntity->getDescendants();
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
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategory($categoryKey)
    {
        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setCategoryKey($categoryKey);
        $categoryTransfer->setIsActive(false);
        $this->addLocalizedAttributesToCategoryTransfer($categoryTransfer);

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setFkCategory($categoryTransfer->getIdCategory());
        $categoryNodeTransfer->setIsMain(false);
        $categoryTransfer->setCategoryNode($categoryNodeTransfer);

        $parentCategoryNodeTransfer = new NodeTransfer();
        $parentCategoryNodeTransfer->setIdCategoryNode(1);
        $categoryTransfer->setParentCategoryNode($parentCategoryNodeTransfer);

        $categoryFacade = new CategoryFacade();
        $categoryFacade->create($categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function addLocalizedAttributesToCategoryTransfer(CategoryTransfer $categoryTransfer)
    {
        $localeTransferCollection = $this->getLocaleTransferCollection();

        foreach ($localeTransferCollection as $localeTransfer) {
            $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
            $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);
            $categoryLocalizedAttributesTransfer->setName(
                $categoryTransfer->getCategoryKey() . ' name ' . $localeTransfer->getLocaleName()
            );
            $categoryLocalizedAttributesTransfer->setMetaTitle(
                $categoryTransfer->getCategoryKey() . ' title ' . $localeTransfer->getLocaleName()
            );
            $categoryLocalizedAttributesTransfer->setMetaDescription(
                $categoryTransfer->getCategoryKey() . ' description ' . $localeTransfer->getLocaleName()
            );
            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getLocaleTransferCollection()
    {
        $localeFacade = new LocaleFacade();
        $localeTransferCollection = $localeFacade->getLocaleCollection();

        return $localeTransferCollection;
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
