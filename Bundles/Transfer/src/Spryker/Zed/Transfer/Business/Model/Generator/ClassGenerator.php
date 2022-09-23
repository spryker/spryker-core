<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use RuntimeException;
use Spryker\Shared\Kernel\Transfer\AbstractAttributesTransfer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ClassGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    public const TWIG_TEMPLATES_LOCATION = '/../../../../../../../templates/generator/';

    /**
     * @var string
     */
    protected string $targetDirectory;

    /**
     * @var \Twig\Environment
     */
    protected Environment $twig;

    /**
     * @param string $targetDirectory
     *
     * @throws \RuntimeException
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . static::TWIG_TEMPLATES_LOCATION);
        if (!$path) {
            throw new RuntimeException(sprintf('Cannot find templates path `%s`', __DIR__ . DIRECTORY_SEPARATOR . static::TWIG_TEMPLATES_LOCATION));
        }
        $loader = new FilesystemLoader($path);
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
        $attributeTransfers = [];
        $valueObjects = [];
        foreach ($definition->getNormalizedProperties() as $property) {
            if ($this->isPropertyTypeCollection($property)) {
                $collections[] = $property;

                continue;
            }

            if ($this->isPropertyTypeAbstractAttributesTransfer($property)) {
                $attributeTransfers[] = $property;

                continue;
            }

            if ($this->isPropertyTypeTransfer($property)) {
                $transfers[] = $property;

                continue;
            }

            if ($this->isPropertyTypeValueObject($property)) {
                $valueObjects[] = $property;

                continue;
            }

            $primitives[] = $property;
        }

        return [
            'transferCollections' => $collections,
            'primitives' => $primitives,
            'transfers' => $transfers,
            'attributeTransfers' => $attributeTransfers,
            'valueObjects' => $valueObjects,
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
            'useStatements' => $classDefinition->getUseStatements(),
            'entityNamespace' => $classDefinition->getEntityNamespace(),
            'isDebugMode' => $classDefinition->isDebugMode(),
        ];
        $twigVariables += $this->getPropertiesSegregatedByType($classDefinition);

        return $twigVariables;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isPropertyTypeAbstractAttributesTransfer(array $property): bool
    {
        return $property['is_transfer'] && $property['type_fully_qualified'] === AbstractAttributesTransfer::class;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isPropertyTypeTransfer(array $property): bool
    {
        return $property['is_transfer'];
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isPropertyTypeCollection(array $property): bool
    {
        return $property['is_collection'];
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isPropertyTypeValueObject(array $property): bool
    {
        return $property['is_value_object'];
    }
}
