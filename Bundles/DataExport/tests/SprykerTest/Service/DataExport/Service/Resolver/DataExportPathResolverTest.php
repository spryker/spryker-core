<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Service\DataExport\Service\Resolver;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Service\DataExport\Resolver\DataExportPathResolver;

/**
 * Auto-generated group annotations
 *
 * @group Service
 * @group DataExport
 * @group Service
 * @group Resolver
 * @group DataExportPathResolverTest
 * Add your own group annotations below this line
 */
class DataExportPathResolverTest extends Unit
{
    protected const EXPORT_ROOT_DIR = '{application_root_dir}';
    protected const DESTINATION = '{data_entity}s_{timestamp}.{extension}';
    protected const DATA_ENTITY = 'test-data-entity';
    protected const EXTENSION = 'csv';

    protected const HOOK_KEY_APPLICATION_ROOT_DIR = 'application_root_dir';
    protected const HOOK_KEY_DATA_ENTITY = 'data_entity';
    protected const HOOK_KEY_TIMESTAMP = 'timestamp';
    protected const HOOK_KEY_EXTENSION = 'extension';

    /**
     * @return void
     */
    public function testResolvePathWillResolvePathWithPlaceholders(): void
    {
        //Arrange
        $timestamp = time();
        $hooks = [
            static::HOOK_KEY_TIMESTAMP => $timestamp,
            static::HOOK_KEY_DATA_ENTITY => static::DATA_ENTITY,
            static::HOOK_KEY_EXTENSION => static::EXTENSION,
            static::HOOK_KEY_APPLICATION_ROOT_DIR => APPLICATION_ROOT_DIR,
        ];

        $resultDestination = (new DataExportConfigurationTransfer())
            ->setDestination(static::DESTINATION)
            ->setHooks($hooks);

        //Act
        $resultDestination = (new DataExportPathResolver())->resolvePath(
            $resultDestination,
            static::EXPORT_ROOT_DIR
        );

        //Assert
        $expectedDestination = sprintf(
            '%s/%ss_%d.%s',
            APPLICATION_ROOT_DIR,
            static::DATA_ENTITY,
            $timestamp,
            static::EXTENSION
        );

        $this->assertEquals(
            $expectedDestination,
            $resultDestination,
            'Resolved path does not equals to an expected value.'
        );
    }
}
