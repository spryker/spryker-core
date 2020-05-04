<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface getRepository()
 */
class ProductLabelRelationUpdaterConsole extends Console
{
    public const COMMAND_NAME = 'product-label:relations:update';

    protected const OPTION_NO_TOUCH = 'no-touch';
    protected const OPTION_NO_TOUCH_SHORT = 't';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->addOption(
            static::OPTION_NO_TOUCH,
            static::OPTION_NO_TOUCH_SHORT,
            InputOption::VALUE_NONE,
            'Disable the touch operations.'
        );
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
        $this->getFacade()->updateDynamicProductLabelRelations($this->getMessenger(), $this->getWithTouchOption($input));

        return null;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return bool
     */
    protected function getWithTouchOption(InputInterface $input): bool
    {
        return !$input->getOption(static::OPTION_NO_TOUCH);
    }
}
