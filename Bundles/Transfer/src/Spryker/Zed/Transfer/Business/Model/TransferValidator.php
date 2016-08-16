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

class TransferValidator
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
        'int' => 'int',
        'bool' => 'bool',
        'integer' => 'int',
        'boolean' => 'bool',
        'string' => 'string',
        'float' => 'float',
        'array' => 'array'
    ];

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface $finder
     */
    public function __construct(LoggerInterface $messenger, FinderInterface $finder)
    {
        $this->messenger = $messenger;
        $this->finder = $finder;
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
            if ($options['bundle']) {
                if (strpos($file, '/Shared/' . $options['bundle'] . '/Transfer/') === false) {
                    continue;
                }
            }

            $definition = Factory::fromFile($file->getPathname(), true)->toArray();
            $definition = $this->normalize($definition);

            $bundle = $this->getBundleFromPathName($file->getFilename());
            $result = $result & $this->validateDefinition($bundle, $definition, $options);
        }

        return $result;
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
                $type = strtolower($property['type']);
                if (!isset($this->typeMap[$type])) {
                    continue;
                }

                if ($type === $property['type'] && in_array($type, $this->typeMap)) {
                    continue;
                }

                $ok = false;
                $this->messenger->warning(sprintf(
                    '%s.%s.%s: %s should be %s',
                    $bundle,
                    $transfer['name'],
                    $property['name'],
                    $property['type'],
                    $this->typeMap[$type]
                ));
            }
        }

        return $ok;
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
