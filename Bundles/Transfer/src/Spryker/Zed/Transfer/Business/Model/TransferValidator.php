<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Laminas\Config\Factory;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface;
use Spryker\Zed\Transfer\Business\XmlValidator\XmlValidatorInterface;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Finder\SplFileInfo;

class TransferValidator implements TransferValidatorInterface
{
    public const TRANSFER_SCHEMA_SUFFIX = '.transfer.xml';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface
     */
    protected $finder;

    /**
     * @var array
     */
    protected $typeMap;

    /**
     * @var array
     */
    protected $simpleTypeMap = [
        'integer' => 'int',
        'boolean' => 'bool',
        'array' => 'array',
        '[]' => 'array (or more concrete `type[]`)',
        'mixed[]' => 'array (or more concrete `type[]`)',
        'integer[]' => 'int[]',
        'boolean[]' => 'bool[]',
    ];

    /**
     * @var array
     */
    protected $simpleTypeWhitelist = [
        'int',
        'float',
        'decimal',
        'string',
        'bool',
        'callable',
        'iterable',
        'iterator',
        'resource',
        'object',
    ];

    /**
     * @var \Spryker\Zed\Transfer\TransferConfig
     */
    protected $transferConfig;

    /**
     * @var \Spryker\Zed\Transfer\Business\XmlValidator\XmlValidatorInterface
     */
    protected $xmlValidator;

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface $finder
     * @param \Spryker\Zed\Transfer\TransferConfig $transferConfig
     * @param \Spryker\Zed\Transfer\Business\XmlValidator\XmlValidatorInterface $xmlValidator
     */
    public function __construct(
        LoggerInterface $messenger,
        FinderInterface $finder,
        TransferConfig $transferConfig,
        XmlValidatorInterface $xmlValidator
    ) {
        $this->messenger = $messenger;
        $this->finder = $finder;
        $this->transferConfig = $transferConfig;

        $this->typeMap = $this->simpleTypeMap;
        foreach ($this->simpleTypeWhitelist as $type) {
            $this->typeMap[$type] = $type;
        }
        $this->xmlValidator = $xmlValidator;
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public function validate(array $options): bool
    {
        $files = $this->finder->getXmlTransferDefinitionFiles();

        $result = true;
        foreach ($files as $key => $file) {
            if ($this->transferConfig->isTransferXmlValidationEnabled()) {
                $result &= $this->validateXml($file);
            }

            if ($options['bundle'] && strpos($file, '/Shared/' . $options['bundle'] . '/Transfer/') === false) {
                continue;
            }

            $definition = Factory::fromFile($file->getPathname(), true)->toArray();
            $definition = $this->normalize($definition);

            $module = $this->getModuleFromPathName($file->getFilename());

            if ($options['verbose']) {
                $this->messenger->info(sprintf('Checking %s module (%s)', $module, $file->getFilename()));
            }

            $result &= $this->validateDefinition($module, $definition, $options);
        }

        return (bool)$result;
    }

    /**
     * @param string $module
     * @param array $definition
     * @param array $options
     *
     * @return bool
     */
    protected function validateDefinition(string $module, array $definition, array $options): bool
    {
        $isValid = true;
        foreach ($definition as $transfer) {
            if ($this->transferConfig->isTransferNameValidated() && !$this->isValidName($transfer['name'])) {
                $isValid = false;
                $this->messenger->warning(sprintf(
                    '%s.%s is an invalid transfer name',
                    $module,
                    $transfer['name']
                ));
            }

            if ($this->isTransferEmpty($transfer)) {
                continue;
            }

            $singulars = [];
            foreach ($transfer['property'] as $property) {
                $type = $property['type'];

                if ($this->isArrayType($type) && !$this->isValidArrayType($type)) {
                    $isValid = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s: %s is an invalid array type',
                        $module,
                        $transfer['name'],
                        $property['name'],
                        $type
                    ));

                    continue;
                }

                if (!$this->isValidSimpleType($type)) {
                    $isValid = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s: %s is an invalid simple type',
                        $module,
                        $transfer['name'],
                        $property['name'],
                        $type
                    ));

                    continue;
                }

                if ($this->isSingularRequired($type) && !$this->hasSingularName($property)) {
                    $isValid = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s is a collection but does not have a singular name defined.',
                        $module,
                        $transfer['name'],
                        $property['name']
                    ));

                    continue;
                }

                if ($this->transferConfig->isCaseValidated() && !$this->isValidPropertyName($property['name'])) {
                    $isValid = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s is an invalid property name',
                        $module,
                        $transfer['name'],
                        $property['name']
                    ));

                    continue;
                }
                if ($this->transferConfig->isCaseValidated() && !$this->hasValidSingularName($property)) {
                    $isValid = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s: %s is an invalid singular name',
                        $module,
                        $transfer['name'],
                        $property['name'],
                        $property['singular']
                    ));

                    continue;
                }
                if ($this->hasSingularName($property)) {
                    $singulars[] = $property['singular'];
                }
            }

            if ($this->hasDuplicate($singulars)) {
                $isValid = false;
                $this->messenger->warning(sprintf(
                    '%s.%s: has same singular name used for different properties',
                    $module,
                    $transfer['name']
                ));

                continue;
            }
        }

        return $isValid;
    }

    /**
     * @param array $transfer
     *
     * @return bool
     */
    protected function isTransferEmpty(array $transfer): bool
    {
        return empty($transfer['property']);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isValidSimpleType(string $type): bool
    {
        $whitelist = array_merge($this->simpleTypeWhitelist, ['array']);
        if (in_array($type, $whitelist, true)) {
            return true;
        }

        if (preg_match('/^[A-Z]/', $type) || substr($type, -2) === '[]') {
            return true;
        }

        return false;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isArrayType(string $type): bool
    {
        return (bool)preg_match('#\[\]$#', $type);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isValidArrayType(string $type): bool
    {
        if (!preg_match('#^([a-z][a-z0-9]+)\[\]$#i', $type, $matches)) {
            return false;
        }

        if (!preg_match('#^([a-z]+)\[\]$#', $type, $matches)) {
            return true;
        }

        $extractedType = $matches[1];
        $extractedTypeLowercase = strtolower($extractedType);

        if (!in_array($extractedTypeLowercase, $this->simpleTypeWhitelist, true)) {
            return false;
        }

        if ($extractedTypeLowercase === $extractedType && in_array($extractedType, $this->simpleTypeWhitelist, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $definition
     *
     * @return array
     */
    protected function normalize(array $definition): array
    {
        $transferDefinition = $definition['transfer'];
        if (!isset($transferDefinition[0])) {
            $transferDefinition = [$transferDefinition];
        }

        foreach ($transferDefinition as $key => $transfer) {
            foreach ($transfer as $singleKey => $single) {
                if (isset($single[0])) {
                    continue;
                }

                $transferDefinition[$key][$singleKey] = [$single];
            }
        }

        return $transferDefinition;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getModuleFromPathName(string $fileName): string
    {
        $filter = new UnderscoreToCamelCase();

        return (string)$filter->filter(str_replace(self::TRANSFER_SCHEMA_SUFFIX, '', $fileName));
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isValidName(string $name): bool
    {
        if (!preg_match('/^[A-Z][a-zA-Z0-9]/', $name)) {
            return false;
        }

        if (preg_match('/Transfer$/', $name)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isValidPropertyName(string $name): bool
    {
        if (!preg_match('/^[a-z]/', $name)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isSingularRequired(string $type): bool
    {
        if (!$this->transferConfig->isSingularRequired()) {
            return false;
        }

        return ($type === 'array' || $this->isArrayType($type));
    }

    /**
     * @param string[] $property
     *
     * @return bool
     */
    protected function hasSingularName(array $property): bool
    {
        return !empty($property['singular']);
    }

    /**
     * @param string[] $property
     *
     * @return bool
     */
    protected function hasValidSingularName(array $property): bool
    {
        if (!isset($property['singular'])) {
            return true;
        }

        return $this->isValidPropertyName($property['singular']);
    }

    /**
     * @param string[] $singulars
     *
     * @return bool
     */
    protected function hasDuplicate(array $singulars): bool
    {
        return count(array_unique($singulars)) < count($singulars);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return bool
     */
    protected function validateXml(SplFileInfo $fileInfo): bool
    {
        $this->xmlValidator->validate(
            $fileInfo->getRealPath(),
            $this->transferConfig->getXsdSchemaFilePath()
        );

        if ($this->xmlValidator->isValid()) {
            return true;
        }

        foreach ($this->xmlValidator->getErrors() as $error) {
            $this->messenger->warning($error);
        }

        return false;
    }
}
