<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabel\Business\Label\LabelReader;
use Spryker\Zed\ProductLabel\Business\Label\LabelWriter;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface getQueryContainer()
 */
class ProductLabelBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LabelWriterInterface
     */
    public function createLabelWriter()
    {
        return new LabelWriter();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\Label\LabelReaderInterface
     */
    public function createLabelReader()
    {
        return new LabelReader($this->getQueryContainer());
    }

}
