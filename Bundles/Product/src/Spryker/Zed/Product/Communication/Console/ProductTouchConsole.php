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
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 */
class ProductTouchConsole extends Console
{
    /**
     * @var string
     */
    public const ACTION_ACTIVATE = 'activate';

    /**
     * @var string
     */
    public const ACTION_ACTIVATE_SHORT = 'a';

    /**
     * @var string
     */
    public const ACTION_INACTIVATE = 'inactivate';

    /**
     * @var string
     */
    public const ACTION_INACTIVATE_SHORT = 'i';

    /**
     * @var string
     */
    public const ACTION_DELETE = 'delete';

    /**
     * @var string
     */
    public const ACTION_DELETE_SHORT = 'd';

    /**
     * @var string
     */
    public const COMMAND_NAME = 'product:touch';

    /**
     * @var string
     */
    public const DESCRIPTION = 'product:touch <activate|inactivate|delete|a|i|d> <id>';

    /**
     * @var string
     */
    public const ARGUMENT_ID_ABSTRACT_PRODUCT = 'id_product_abstract';

    /**
     * @var string
     */
    public const ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION = 'The `id_product_abstract` id of the record to be touched.';

    /**
     * @var string
     */
    public const ARGUMENT_TOUCH_ACTION = 'action';

    /**
     * @var string
     */
    public const ARGUMENT_TOUCH_ACTION_DESCRIPTION = 'The `touch action` can be one of the following: `active`, `inactive`, `deleted` or just the first letter.';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addArgument(
            static::ARGUMENT_TOUCH_ACTION,
            InputArgument::REQUIRED,
            static::ARGUMENT_TOUCH_ACTION_DESCRIPTION,
        );

        $this->addArgument(
            static::ARGUMENT_ID_ABSTRACT_PRODUCT,
            InputArgument::REQUIRED,
            static::ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION,
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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $idProductAbstract = (int)$input->getArgument(static::ARGUMENT_ID_ABSTRACT_PRODUCT);
        $action = strtolower($input->getArgument(static::ARGUMENT_TOUCH_ACTION));

        switch ($action) {
            case static::ACTION_ACTIVATE:
            case static::ACTION_ACTIVATE_SHORT:
                $this->getFacade()->touchProductActive($idProductAbstract);

                break;
            case static::ACTION_INACTIVATE:
            case static::ACTION_INACTIVATE_SHORT:
                $this->getFacade()->touchProductInactive($idProductAbstract);

                break;
            case static::ACTION_DELETE:
            case static::ACTION_DELETE_SHORT:
                $this->getFacade()->touchProductDeleted($idProductAbstract);

                break;
            default:
                throw new Exception('Unknown touch action: ' . $action);
        }

        return static::CODE_SUCCESS;
    }
}
