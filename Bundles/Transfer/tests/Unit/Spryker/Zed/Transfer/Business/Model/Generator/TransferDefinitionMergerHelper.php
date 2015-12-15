<?php

namespace Unit\Spryker\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionMergerHelper
{

    /**
     * @return array
     */
    public function getTransferDefinition1()
    {
        return [
            'name' => 'Transfer',
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
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
    public function getTransferDefinition2()
    {
        return [
            'name' => 'Transfer',
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle2',
                    ],
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
    public function getExpectedTransfer()
    {
        return [
            'name' => 'Transfer',
            'property' => [
                'propertyA' => [
                    'name' => 'propertyA',
                    'type' => 'string',
                    'bundles' => [
                        'Bundle1',
                        'Bundle2',
                    ],
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
