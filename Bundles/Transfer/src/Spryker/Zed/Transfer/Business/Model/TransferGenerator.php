<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface;

class TransferGenerator implements TransferGeneratorInterface
{

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface
     */
    private $generator;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    private $definitionBuilder;

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface $generator
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface $definitionBuilder
     */
    public function __construct(LoggerInterface $messenger, GeneratorInterface $generator, DefinitionBuilderInterface $definitionBuilder)
    {
        $this->messenger = $messenger;
        $this->generator = $generator;
        $this->definitionBuilder = $definitionBuilder;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $definitions = $this->definitionBuilder->getDefinitions();

        foreach ($definitions as $classDefinition) {
            $fileName = $this->generator->generate($classDefinition);

            $this->messenger->info(sprintf('<info>%s</info> was generated', $fileName));
        }
    }

}
