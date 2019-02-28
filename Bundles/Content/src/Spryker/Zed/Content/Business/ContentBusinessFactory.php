<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business;

use Spryker\Zed\Content\Business\ContentReader\ContentReader;
use Spryker\Zed\Content\Business\ContentReader\ContentReaderInterface;
use Spryker\Zed\Content\Business\ContentWriter\ContentWriter;
use Spryker\Zed\Content\Business\ContentWriter\ContentWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Content\ContentConfig getConfig()
 * @method \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Content\Persistence\ContentRepositoryInterface getRepository()
 */
class ContentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Content\Business\ContentWriter\ContentWriterInterface
     */
    public function createContentWriter(): ContentWriterInterface
    {
        return new ContentWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\Content\Business\ContentReader\ContentReaderInterface
     */
    public function createContentReader(): ContentReaderInterface
    {
        return new ContentReader(
            $this->getRepository()
        );
    }
}
