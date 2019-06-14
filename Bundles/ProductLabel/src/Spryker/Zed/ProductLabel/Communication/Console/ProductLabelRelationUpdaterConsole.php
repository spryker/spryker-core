<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface getQueryContainer()
 */
class ProductLabelRelationUpdaterConsole extends Console
{
    public const COMMAND_NAME = 'product-label:relations:update';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Updates Product Label relations based on the registered plugins.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->updateDynamicProductLabelRelations($this->getMessenger());

        return null;
    }
}
