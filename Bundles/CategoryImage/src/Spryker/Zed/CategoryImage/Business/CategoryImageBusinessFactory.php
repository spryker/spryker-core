<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use Spryker\Zed\CategoryImage\Business\ImageSet\CategoryExpander;
use Spryker\Zed\CategoryImage\Business\ImageSet\CategoryExpanderInterface;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetCreator;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetCreatorInterface;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetDeleter;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetDeleterInterface;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetReader;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetReaderInterface;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetUpdater;
use Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetUpdaterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 */
class CategoryImageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetReaderInterface
     */
    public function createImageSetReader(): ImageSetReaderInterface
    {
        return new ImageSetReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\ImageSet\CategoryExpanderInterface
     */
    public function createCategoryExpander(): CategoryExpanderInterface
    {
        return new CategoryExpander(
            $this->createImageSetReader()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetCreatorInterface
     */
    public function createImageSetCreator(): ImageSetCreatorInterface
    {
        return new ImageSetCreator(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetUpdaterInterface
     */
    public function createImageSetUpdated(): ImageSetUpdaterInterface
    {
        return new ImageSetUpdater(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetDeleterInterface
     */
    public function createImageSetDeleter(): ImageSetDeleterInterface
    {
        return new ImageSetDeleter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
