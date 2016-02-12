<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacade getFacade()
 */
class ProductTouchConsole extends Console
{

    const COMMAND_NAME = 'product:touch';
    const DESCRIPTION = 'Touch an Abstract Product';

    const ARGUMENT_ID_ABSTRACT_PRODUCT = 'id_product_abstract';
    const ARGUMENT_ID_ABSTRACT_PRODUCT_DESCRIPTION = 'The `id_product_abstract` id of the record to be touched.';

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
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArguments();
        $idProductAbstract = $arguments[self::ARGUMENT_ID_ABSTRACT_PRODUCT];

        $product = $this->getFacade();
        $product->touchProductActive($idProductAbstract);

        return self::CODE_SUCCESS;
    }

}
