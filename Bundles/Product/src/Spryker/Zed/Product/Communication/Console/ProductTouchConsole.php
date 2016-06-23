<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacade getFacade()
 */
class ProductTouchConsole extends Console
{

    const COMMAND_NAME = 'touch:product';
    const DESCRIPTION = 'Touch an Abstract Product';

    const ARGUMENT_ID_ABSTRACT_PRODUCT = 'id_product_abstract';
    const ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION = 'The `id_product_abstract` id of the record to be touched.';

    const ARGUMENT_TOUCH_ACTION = 'action';
    const ARGUMENT_TOUCH_ACTION_DESCRIPTION = 'The `touch action` can be one of the following: active, inactive, deleted';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(
            self::ARGUMENT_ID_ABSTRACT_PRODUCT,
            InputArgument::REQUIRED,
            self::ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION
        );

        $this->addArgument(
            self::ARGUMENT_TOUCH_ACTION,
            InputArgument::REQUIRED,
            self::ARGUMENT_TOUCH_ACTION_DESCRIPTION
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $idProductAbstract = (int) $input->getArgument(self::ARGUMENT_ID_ABSTRACT_PRODUCT);
        $action = strtolower($input->getArgument(self::ARGUMENT_TOUCH_ACTION));

        switch ($action) {
            case 'active':
                $this->getFacade()->touchProductActive($idProductAbstract);
                break;
            case 'inactive':
                $this->getFacade()->touchProductInActive($idProductAbstract);
                break;
            case 'delete':
                $this->getFacade()->touchProductDeleted($idProductAbstract);
                break;

            default:
                throw new Exception('Unknown touch action: ' . $action);
                break;
        }




        return self::CODE_SUCCESS;
    }

}
