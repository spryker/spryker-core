<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Console\Business\Model\Fixtures;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class ConsoleMock extends Console
{
    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    public function getFactory(): AbstractCommunicationFactory
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade(): AbstractFacade
    {
        return parent::getFacade();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer|null
     */
    public function getQueryContainer(): ?AbstractQueryContainer
    {
        return parent::getQueryContainer();
    }
}
