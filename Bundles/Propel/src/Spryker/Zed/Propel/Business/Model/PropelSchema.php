<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use RuntimeException;
use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver as SharedAbstractClassResolver;
use Spryker\Shared\Kernel\ClassResolver\Config\SharedConfigNotFoundException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver as ZedAbstractClassResolver;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigNotFoundException;

class PropelSchema implements PropelSchemaInterface
{
    use LoggerTrait;

   /**
    * @var \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface
    */
    protected $finder;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface
     */
    protected $merger;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface $finder
     * @param \Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface $writer
     * @param \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface $merger
     * @param \Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver $bundleConfigResolver
     * @param \Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver $sharedConfigResolver
     */
    public function __construct(
        PropelGroupedSchemaFinderInterface $finder,
        PropelSchemaWriterInterface $writer,
        PropelSchemaMergerInterface $merger,
        protected ZedAbstractClassResolver $bundleConfigResolver,
        protected SharedAbstractClassResolver $sharedConfigResolver
    ) {
        $this->finder = $finder;
        $this->writer = $writer;
        $this->merger = $merger;
    }

    /**
     * @return void
     */
    public function copy()
    {
        $schemaFiles = $this->finder->getGroupedSchemaFiles();

        foreach ($schemaFiles as $fileName => $groupedSchemas) {
            $groupedSchemas = $this->getGroupedSchemasWithOptionalFeatures($groupedSchemas);

            if ($this->needMerge($groupedSchemas)) {
                $content = $this->merger->merge($groupedSchemas);
            } else {
                $content = $this->getCurrentSchemaContent($groupedSchemas);
            }
            $this->writer->write($fileName, $content);
        }
    }

    /**
     * @param array $groupedSchemas
     *
     * @return bool
     */
    protected function needMerge(array $groupedSchemas)
    {
        return (count($groupedSchemas) > 1);
    }

    /**
     * @param array<\Symfony\Component\Finder\SplFileInfo> $groupedSchemas
     *
     * @return string
     */
    protected function getCurrentSchemaContent(array $groupedSchemas)
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $schemaFile */
        $schemaFile = current($groupedSchemas);

        return $schemaFile->getContents();
    }

    /**
     * @param array $groupedSchemas
     *
     * @return array
     */
    protected function getGroupedSchemasWithOptionalFeatures(array $groupedSchemas): array
    {
        $filteredGroupedSchemas = array_filter($groupedSchemas, function ($schemaFile) {
            if (preg_match('#\/Zed\/(\w+)\/Persistence\/Propel\/Schema\/(\w+)\/#', $schemaFile->getRealPath(), $matches)) {
                $moduleName = $matches[1]; // e.g., "ProductImage"
                $featureName = $matches[2]; // e.g., "DiscountSortPriority"
                $configFeatureEnabledMethod = 'is' . $featureName . 'Enabled';

                $this->getLogger()->info('Optional DB schema is detected in module `' . $moduleName . '` for feature `' . $featureName . '`.');

                try {
                    $moduleConfig = $this->bundleConfigResolver->resolve($moduleName);
                    $sharedModuleConfig = $this->sharedConfigResolver->resolve($moduleName);
                } catch (BundleConfigNotFoundException | SharedConfigNotFoundException $e) {
                    throw new RuntimeException(
                        sprintf(
                            'Config class for module `%s` not found. This folder `%s` enables optional DB schema via module config.',
                            $moduleName,
                            $schemaFile->getRealPath(),
                        ),
                    );
                }

                if (!method_exists($moduleConfig, $configFeatureEnabledMethod) && !method_exists($sharedModuleConfig, $configFeatureEnabledMethod)) {
                    throw new RuntimeException(
                        sprintf(
                            'Config method `%s::%s()` or `%s::%s()` not found. This folder `%s` requires that method to enable optional DB schema.',
                            $moduleConfig::class,
                            $configFeatureEnabledMethod,
                            $sharedModuleConfig::class,
                            $configFeatureEnabledMethod,
                            $schemaFile->getRealPath(),
                        ),
                    );
                }

                if (
                    (method_exists($moduleConfig, $configFeatureEnabledMethod) && $moduleConfig->$configFeatureEnabledMethod()) ||
                    (method_exists($sharedModuleConfig, $configFeatureEnabledMethod) && $sharedModuleConfig->$configFeatureEnabledMethod())
                ) {
                    $this->getLogger()->info('Adding optional feature `' . $featureName . '` to the module `' . $moduleName . '` schema.');

                    return true;
                }

                $this->getLogger()->info('The optional feature `' . $featureName . '` is disabled in the module `' . $moduleName . '`. Skipping schema file: ' . $schemaFile->getRealPath());

                return false;
            }

            return true;
        });

        return $filteredGroupedSchemas;
    }
}
