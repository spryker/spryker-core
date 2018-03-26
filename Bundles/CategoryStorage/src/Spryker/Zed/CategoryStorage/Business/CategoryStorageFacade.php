<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageBusinessFactory getFactory()
 */
class CategoryStorageFacade extends AbstractFacade implements CategoryStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds)
    {
        $this->getFactory()->createCategoryNodeStorage()->publish($categoryNodeIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds)
    {
        $this->getFactory()->createCategoryNodeStorage()->unpublish($categoryNodeIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function publishCategoryTree()
    {
        $this->getFactory()->createCategoryTreeStorage()->publish();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function unpublishCategoryTree()
    {
        $this->getFactory()->createCategoryTreeStorage()->unpublish();
    }
}
