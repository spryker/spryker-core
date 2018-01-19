<?php

namespace Spryker\Zed\CategoryPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchBusinessFactory getFactory()
 */
class CategoryPageSearchFacade extends AbstractFacade implements CategoryPageSearchFacadeInterface
{

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds)
    {
        $this->getFactory()->createCategoryNodeSearchListener()->publish($categoryNodeIds);
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds)
    {
        $this->getFactory()->createCategoryNodeSearchListener()->unpublish($categoryNodeIds);
    }

}
