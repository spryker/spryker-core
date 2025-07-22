<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionMergerHelper
{
    /**
     * @return array
     */
    public function getTransferDefinition1(): array
    {
        return [
            'name' => 'Transfer',
            'entity-namespace' => null,
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle1',
                    ],
                    'dataBuilderRule' => 'shuffle(array("test"))',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTransferDefinition2(): array
    {
        return [
            'name' => 'Transfer',
            'entity-namespace' => null,
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle2',
                    ],
                    'dataBuilderRule' => 'shuffle(array("test"))',
                ],
                [
                    'name' => 'propertyB',
                    'type' => 'int',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getExpectedTransfer(): array
    {
        return [
            'name' => 'Transfer',
            'entity-namespace' => null,
            'deprecated' => null,
            'property' => [
                'propertyA' => [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle1',
                        'Bundle2',
                    ],
                    'dataBuilderRule' => 'shuffle(array("test"))',
                ],
                'propertyB' => [
                    'name' => 'propertyB',
                    'type' => 'int',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getItemMetadataTransfer(): array
    {
        return [
            'name' => 'ItemMetadataTransfer',
            'entity-namespace' => null,
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle1',
                    ],
                ],
                [
                    'name' => 'propertyB',
                    'type' => 'int',
                    'bundles' => [
                        'Bundle1',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getItemMetadata(): array
    {
        return [
            'name' => 'ItemMetadata',
            'entity-namespace' => null,
            'property' => [
                [
                    'name' => 'propertyC',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
                [
                    'name' => 'propertyD',
                    'type' => 'array',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getExpectedMergedItemMetadataTransfer(): array
    {
        return [
            'name' => 'ItemMetadataTransfer',
            'entity-namespace' => null,
            'deprecated' => null,
            'property' => [
                'propertyA' => [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle1',
                    ],
                ],
                'propertyB' => [
                    'name' => 'propertyB',
                    'type' => 'int',
                    'bundles' => [
                        'Bundle1',
                    ],
                ],
                'propertyC' => [
                    'name' => 'propertyC',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
                'propertyD' => [
                    'name' => 'propertyD',
                    'type' => 'array',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getExpectedMergedItemMetadata(): array
    {
        return [
            'name' => 'ItemMetadata',
            'entity-namespace' => null,
            'deprecated' => null,
            'property' => [
                'propertyA' => [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle1',
                    ],
                ],
                'propertyB' => [
                    'name' => 'propertyB',
                    'type' => 'int',
                    'bundles' => [
                        'Bundle1',
                    ],
                ],
                'propertyC' => [
                    'name' => 'propertyC',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
                'propertyD' => [
                    'name' => 'propertyD',
                    'type' => 'array',
                    'bundles' => [
                        'Bundle2',
                    ],
                ],
            ],
        ];
    }
}
