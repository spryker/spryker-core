<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Twig_Environment;
use Twig_Loader_Filesystem;

class DataBuilderClassGenerator implements GeneratorInterface
{
    const TWIG_TEMPLATES_LOCATION = '/Templates/';

    /**
     * @var string
     */
    protected $targetDirectory;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param string $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        $loader = new Twig_Loader_Filesystem(__DIR__ . self::TWIG_TEMPLATES_LOCATION);
        $this->twig = new Twig_Environment($loader, []);
    }

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition)
    {
        /** @var \Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinition $definition */
        $twigData = $this->getTwigData($definition);
        $fileName = $definition->getName() . '.php';
        $fileContent = $this->twig->render('builder.php.twig', $twigData);

        if (!is_dir($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0775, true);
        }

        file_put_contents($this->targetDirectory . $fileName, $fileContent);

        return $fileName;
    }

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinition $dataBuilderDefinition
     *
     * @return array
     */
    public function getTwigData(DataBuilderDefinitionInterface $dataBuilderDefinition)
    {
        return [
            'className' => $dataBuilderDefinition->getName(),
            'transferName' => $dataBuilderDefinition->getTransferName(),
            'rules' => $dataBuilderDefinition->getRules(),
            'dependencies' => $dataBuilderDefinition->getDependencies(),
        ];
    }
}
