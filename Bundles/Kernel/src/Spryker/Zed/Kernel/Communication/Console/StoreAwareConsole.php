<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * @method \Spryker\Zed\Kernel\Communication\KernelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Kernel\Business\KernelFacadeInterface getFacade()
 */
abstract class StoreAwareConsole extends Console
{
    /**
     * @var string
     */
    protected const OPTION_STORE = 'store';

    /**
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->addOption(static::OPTION_STORE, '', InputOption::VALUE_OPTIONAL, 'Executes for concrete store only');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string|null
     */
    protected function getStore(InputInterface $input): ?string
    {
        $store = null;

        if ($input->hasOption(static::OPTION_STORE)) {
            /** @phpstan-var string|null */
            $store = $input->getOption(static::OPTION_STORE);
        }

        if (!$store && defined('APPLICATION_STORE')) {
            $store = APPLICATION_STORE;
        }

        return $store;
    }
}
