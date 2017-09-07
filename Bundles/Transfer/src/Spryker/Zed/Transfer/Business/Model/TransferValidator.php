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

    const TRANSFER_SCHEMA_SUFFIX = '.transfer.xml';

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
    protected $typeMap = [
        'integer' => 'int',
        'boolean' => 'bool',
        '[]' => 'array (or more concrete `type[]`)',
        'mixed[]' => 'array (or more concrete `type[]`)',
        'integer[]' => 'int[]',
        'boolean[]' => 'bool[]',
    ];

    /**
     * @var array
     */
    protected $arrayTypeMap = [
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
     * @param \Psr\Log\LoggerInterface $messenger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface $finder
     */
    public function __construct(LoggerInterface $messenger, FinderInterface $finder)
    {
        $this->messenger = $messenger;
        $this->finder = $finder;

        foreach ($this->arrayTypeMap as $type) {
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

            $bundle = $this->getBundleFromPathName($file->getFilename());
            $result = $result & $this->validateDefinition($bundle, $definition, $options);
        }

        return (bool)$result;
    }

    /**
     * @param string $bundle
     * @param array $definition
     * @param array $options
     *
     * @return bool
     */
    protected function validateDefinition($bundle, array $definition, array $options)
    {
        if ($options['verbose']) {
            $this->messenger->info(sprintf('Checking %s bundle', $bundle));
        }

        $ok = true;
        foreach ($definition as $transfer) {
            foreach ($transfer['property'] as $property) {
                $type = $property['type'];

                if (!$this->checkSimpleType($type)) {
                    $ok = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s: %s should be %s',
                        $bundle,
                        $transfer['name'],
                        $property['name'],
                        $property['type'],
                        $this->typeMap[strtolower($type)]
                    ));
                    continue;
                }

                if (!$this->checkArrayType($type)) {
                    $ok = false;
                    $this->messenger->warning(sprintf(
                        '%s.%s.%s: %s is an invalid array type',
                        $bundle,
                        $transfer['name'],
                        $property['name'],
                        $property['type']
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
    protected function checkSimpleType($type)
    {
        $typeLowercase = strtolower($type);

        if (!isset($this->typeMap[$typeLowercase])) {
            return true;
        }

        if ($typeLowercase === $type && in_array($type, $this->typeMap, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function checkArrayType($type)
    {
        if (!preg_match('#^([a-z]+)\[\]$#', $type, $matches)) {
            return true;
        }

        $extractedType = $matches[1];
        $extractedTypeLowercase = strtolower($extractedType);

        if (!in_array($extractedTypeLowercase, $this->arrayTypeMap)) {
            return false;
        }

        if ($extractedTypeLowercase === $extractedType && in_array($extractedType, $this->arrayTypeMap, true)) {
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
    protected function getBundleFromPathName($fileName)
    {
        $filter = new UnderscoreToCamelCase();

        return $filter->filter(str_replace(self::TRANSFER_SCHEMA_SUFFIX, '', $fileName));
    }

}
