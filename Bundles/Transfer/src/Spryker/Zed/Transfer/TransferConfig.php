<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class TransferConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getClassTargetDirectory()
    {
        return rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/Generated/Shared/Transfer/';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDataBuilderTargetDirectory()
    {
        return rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/Generated/Shared/DataBuilder/';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getSourceDirectories()
    {
        $globPatterns = $this->getCoreSourceDirectoryGlobPatterns();
        $globPatterns[] = $this->getApplicationSourceDirectoryGlobPattern();

        $globPatterns = array_merge($globPatterns, $this->getAdditionalSourceDirectoryGlobPatterns());

        return $globPatterns;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getDataBuilderSourceDirectories()
    {
        $globPatterns = $this->getSourceDirectories();

        $globPatterns[] = rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . '/tests/_data';
        $globPatterns[] = rtrim(APPLICATION_VENDOR_DIR, DIRECTORY_SEPARATOR) . '/*/*/tests/_data/';

        return $globPatterns;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTransferFileNamePattern(): string
    {
        return '/(.*?).transfer.xml/';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDataBuilderFileNamePattern()
    {
        return '/(.*?).(databuilder|transfer).xml/';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEntityFileNamePattern()
    {
        return '/(.*?).(schema).xml/';
    }

    /**
     * @deprecated please use TransferConfig::getCoreSourceDirectoryGlobPatterns() instead
     *
     * @return string
     */
    protected function getSprykerCoreSourceDirectoryGlobPattern()
    {
        return rtrim(APPLICATION_VENDOR_DIR, DIRECTORY_SEPARATOR) . '/*/*/src/*/Shared/*/Transfer/';
    }

    /**
     * @return string[]
     */
    protected function getCoreSourceDirectoryGlobPatterns()
    {
        /**
         * This is added for keeping the BC and needs to be
         * replaced with the actual return of
         * getSprykerCoreSourceDirectoryGlobPattern() method
         */
        return [
            $this->getSprykerCoreSourceDirectoryGlobPattern(),
        ];
    }

    /**
     * @return string
     */
    protected function getApplicationSourceDirectoryGlobPattern()
    {
        return rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/*/Shared/*/Transfer/';
    }

    /**
     * @deprecated please use TransferConfig::getCoreSourceDirectoryGlobPatterns() instead
     *
     * This method can be used to extend the list of directories for transfer object
     * discovery in project implementations.
     *
     * @return string[]
     */
    protected function getAdditionalSourceDirectoryGlobPatterns()
    {
        return [];
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Propel\Business\PropelFacadeInterface::getSchemaDirectory()} instead.
     *
     * @return string[]
     */
    public function getEntitiesSourceDirectories()
    {
        return [
            rtrim(APPLICATION_SOURCE_DIR, DIRECTORY_SEPARATOR) . '/Orm/Propel/' . APPLICATION_STORE . '/Schema/',
        ];
    }

    /**
     * This will enable strict validation for transfer names upon generation.
     * The suffix "Transfer" is auto-appended and must not be inside the XML definitions.
     *
     * Defaults to false for BC reasons. Enable on project level if all modules in question
     * have been upgraded to the version they are fixed in.
     *
     * @api
     *
     * @return bool
     */
    public function isTransferNameValidated(): bool
    {
        return false;
    }

    /**
     * This will enable strict validation for case sensitive declaration.
     * Mainly for property names, and singular definition.
     *
     * Defaults to false for BC reasons. Enable on project level if all modules in question
     * have been upgraded to the version they are fixed in.
     *
     * @api
     *
     * @return bool
     */
    public function isCaseValidated(): bool
    {
        return false;
    }

    /**
     * This will enable strict validation for collections and singular definition.
     * The singular here is important to specify to avoid it being generated without inflection.
     *
     * Defaults to false for BC reasons. Enable on project level if all modules in question
     * have been upgraded to the version they comply with this rule.
     *
     * @api
     *
     * @return bool
     */
    public function isSingularRequired(): bool
    {
        return false;
    }

    /**
     * Gets shim from=>to map per transfer field that was wrongly set up in core level.
     * Since transfers are not "owned" by a particular module, this applies here transfer internal on a core level
     * as a whole.
     *
     * This list can be reduced on project level where needed (e.g. to preserve full BC in edge cases).
     * But we recommend to fix the project code instead fo use the same intended type as the actual type
     * going in and out on core level here.
     *
     * Only scalar values and arrays are allowed to be shimmed and this list is only used from core level perspective.
     * Do not increase this list from project level, it is intended to help projects adapt early to the actual
     * type of core methods.
     *
     * @api
     *
     * @phpstan-return array<string, array<string, array<string, string>>>
     *
     * @return string[][][]
     */
    public function getTypeShims(): array
    {
        return [
            'KeyTranslation' => [
                'glossaryKey' => [
                    'int' => 'string',
                ],
            ],
            'ProductReview' => [
                'status' => [
                    'int' => 'string',
                ],
            ],
            'CheckoutError' => [
                'errorCode' => [
                    'int' => 'string',
                ],
            ],
            'SynchronizationData' => [
                'data' => [
                    'string' => 'array',
                ],
            ],
            'SpyProductQuantityStorageEntity' => [
                'data' => [
                    'string' => 'array',
                ],
            ],
        ];
    }

    /**
     * Specification:
     * - When enabled, an extra-check for type correctness of a setter method argument is done.
     * - Only for core level introspection, do not use for projects.
     *
     * @api
     *
     * @return bool
     */
    public function isSetterTypeAssertionEnabled(): bool
    {
        return true;
    }
}
