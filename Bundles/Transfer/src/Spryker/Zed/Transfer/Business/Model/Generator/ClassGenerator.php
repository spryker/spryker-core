<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ClassGenerator implements GeneratorInterface
{
    public const TWIG_TEMPLATES_LOCATION = '/Templates/';

    /**
     * @var string
     */
    protected $targetDirectory;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @param string $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        $loader = new FilesystemLoader(__DIR__ . self::TWIG_TEMPLATES_LOCATION);
        $this->twig = new Environment($loader, []);
    }

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition): string
    {
        $twigContext = $this->getTwigContext($definition);

        $fileName = $definition->getName() . '.php';

        $fileContent = $this->twig->render('class.php.twig', $twigContext);

        if (!is_dir($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0775, true);
        }

        file_put_contents($this->targetDirectory . $fileName, $fileContent);

        return $fileName;
    }

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface $definition
     *
     * @return array
     */
    public function getPropertiesSegregatedByType(ClassDefinitionInterface $definition): array
    {
        $collections = [];
        $primitives = [];
        $transfers = [];
        foreach ($definition->getNormalizedProperties() as $property) {
            if ($this->isPropertyTypeCollection($property)) {
                $collections[] = $property;
                continue;
            }
            if ($this->isPropertyTypeTransfer($property)) {
                $transfers[] = $property;
                continue;
            }

            $primitives[] = $property;
        }

        return [
            'transferCollections' => $collections,
            'primitives' => $primitives,
            'transfers' => $transfers,
        ];
    }

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface $classDefinition
     *
     * @return array
     */
    public function getTwigContext(ClassDefinitionInterface $classDefinition): array
    {
        $twigVariables = [
            'className' => $classDefinition->getName(),
            'constructorDefinition' => $classDefinition->getConstructorDefinition(),
            'constants' => $classDefinition->getConstants(),
            'properties' => $classDefinition->getProperties(),
            'propertyNameMap' => $classDefinition->getPropertyNameMap(),
            'methods' => $classDefinition->getMethods(),
            'normalizedProperties' => $classDefinition->getNormalizedProperties(),
            'deprecationDescription' => $classDefinition->getDeprecationDescription(),
            'hasArrayObject' => $classDefinition->hasArrayObject(),
            'hasDecimal' => $classDefinition->hasDecimal(),
            'entityNamespace' => $classDefinition->getEntityNamespace(),
        ];
        $twigVariables += $this->getPropertiesSegregatedByType($classDefinition);

        return $twigVariables;
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isPropertyTypeTransfer(array $property): bool
    {
        return $property['is_transfer'];
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isPropertyTypeCollection(array $property): bool
    {
        return $property['is_collection'];
    }
}
