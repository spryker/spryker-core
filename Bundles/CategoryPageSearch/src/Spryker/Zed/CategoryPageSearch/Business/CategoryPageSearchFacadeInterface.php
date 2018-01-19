<?php

namespace Spryker\Zed\CategoryPageSearch\Business;

interface CategoryPageSearchFacadeInterface
{

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds);

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds);
}
