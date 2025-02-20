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
}
