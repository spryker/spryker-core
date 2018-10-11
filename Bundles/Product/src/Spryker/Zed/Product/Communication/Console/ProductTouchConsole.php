<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 */
class ProductTouchConsole extends Console
{
    public const ACTION_ACTIVATE = 'activate';
    public const ACTION_ACTIVATE_SHORT = 'a';
    public const ACTION_INACTIVATE = 'inactivate';
    public const ACTION_INACTIVATE_SHORT = 'i';
    public const ACTION_DELETE = 'delete';
    public const ACTION_DELETE_SHORT = 'd';

    public const COMMAND_NAME = 'product:touch';
    public const DESCRIPTION = 'product:touch <activate|inactivate|delete|a|i|d> <id>';

    public const ARGUMENT_ID_ABSTRACT_PRODUCT = 'id_product_abstract';
    public const ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION = 'The `id_product_abstract` id of the record to be touched.';

    public const ARGUMENT_TOUCH_ACTION = 'action';
    public const ARGUMENT_TOUCH_ACTION_DESCRIPTION = 'The `touch action` can be one of the following: `active`, `inactive`, `deleted` or just the first letter.';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(
            self::ARGUMENT_TOUCH_ACTION,
            InputArgument::REQUIRED,
            self::ARGUMENT_TOUCH_ACTION_DESCRIPTION
        );

        $this->addArgument(
            self::ARGUMENT_ID_ABSTRACT_PRODUCT,
            InputArgument::REQUIRED,
            self::ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $idProductAbstract = (int)$input->getArgument(self::ARGUMENT_ID_ABSTRACT_PRODUCT);
        $action = strtolower($input->getArgument(self::ARGUMENT_TOUCH_ACTION));

        switch ($action) {
            case self::ACTION_ACTIVATE:
            case self::ACTION_ACTIVATE_SHORT:
                $this->getFacade()->touchProductActive($idProductAbstract);
                break;

            case self::ACTION_INACTIVATE:
            case self::ACTION_INACTIVATE_SHORT:
                $this->getFacade()->touchProductInactive($idProductAbstract);
                break;

            case self::ACTION_DELETE:
            case self::ACTION_DELETE_SHORT:
                $this->getFacade()->touchProductDeleted($idProductAbstract);
                break;

            default:
                throw new Exception('Unknown touch action: ' . $action);
        }

        return self::CODE_SUCCESS;
    }
}
