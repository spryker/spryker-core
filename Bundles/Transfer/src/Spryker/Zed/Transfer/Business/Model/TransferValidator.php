<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface;
use Zend\Config\Factory;
use Zend\Filter\Word\UnderscoreToCamelCase;

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
        'string',
        'bool',
        'callable',
        'iterable',
        'iterator',
        'resource',
        'object',
    ];

    /**
     * BC shim to use strict generation only as feature flag to be
     * enabled manually on project level.
     *
     * @var bool
     */
    protected $useStrictGeneration;

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface $finder
     * @param bool $useStrictGeneration
     */
    public function __construct(LoggerInterface $messenger, FinderInterface $finder, bool $useStrictGeneration = false)
    {
        $this->messenger = $messenger;
        $this->finder = $finder;
        $this->useStrictGeneration = $useStrictGeneration;

        $this->typeMap = $this->simpleTypeMap;
        foreach ($this->simpleTypeWhitelist as $type) {
            $this->typeMap[$type] = $type;
        }
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public function validate(array $options)
    {
        $files = $this->finder->getXmlTransferDefinitionFiles();

        $result = true;
        foreach ($files as $key => $file) {
            if ($options['bundle'] && strpos($file, '/Shared/' . $options['bundle'] . '/Transfer/') === false) {
                continue;
            }

            $definition = Factory::fromFile($file->getPathname(), true)->toArray();
            $definition = $this->normalize($definition);

            $module = $this->getModuleFromPathName($file->getFilename());
            $result = $result & $this->validateDefinition($module, $definition, $options);
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
    protected function validateDefinition($module, array $definition, array $options)
    {
        if ($options['verbose']) {
            $this->messenger->info(sprintf('Checking %s module', $module));
        }

        $ok = true;
        foreach ($definition as $transfer) {
            if ($this->useStrictGeneration && !$this->validateName($transfer['name'])) {
                $ok = false;
                $this->messenger->warning(sprintf(
                    '%s.%s is an invalid transfer name',
                    $module,
                    $transfer['name']
                ));
            }

            foreach ($transfer['property'] as $property) {
                $type = $property['type'];

                if (!$this->isValidArrayType($type)) {
                    $ok = false;
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
                    $ok = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s: %s is an invalid simple type',
                        $module,
                        $transfer['name'],
                        $property['name'],
                        $type
                    ));

                    continue;
                }
            }
        }

        return $ok;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isValidSimpleType($type)
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
    protected function isValidArrayType($type)
    {
        if (!preg_match('#^([a-z]+)\[\]$#', $type, $matches)) {
            return true;
        }

        $extractedType = $matches[1];
        $extractedTypeLowercase = strtolower($extractedType);

        if (!in_array($extractedTypeLowercase, $this->simpleTypeWhitelist)) {
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
    protected function normalize(array $definition)
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
    protected function getModuleFromPathName($fileName)
    {
        $filter = new UnderscoreToCamelCase();

        return (string)$filter->filter(str_replace(self::TRANSFER_SCHEMA_SUFFIX, '', $fileName));
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function validateName(string $name): bool
    {
        if (!preg_match('/^[A-Z]/', $name)) {
            return false;
        }

        if (preg_match('/Transfer$/', $name)) {
            return false;
        }

        return true;
    }
}
