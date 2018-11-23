<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business;

use Spryker\Zed\CategoryImage\Business\Model\ImageSet\Reader;
use Spryker\Zed\CategoryImage\Business\Model\ImageSet\ReaderInterface;
use Spryker\Zed\CategoryImage\Business\Model\ImageSet\Writer;
use Spryker\Zed\CategoryImage\Business\Model\ImageSet\WriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImage\CategoryImageConfig getConfig()
 */
class CategoryImageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryImage\Business\Model\ImageSet\ReaderInterface
     */
    public function createCategoryImageReader(): ReaderInterface
    {
        return new Reader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\Model\ImageSet\WriterInterface
     */
    public function createCategoryImageWriter(): WriterInterface
    {
        return new Writer(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
