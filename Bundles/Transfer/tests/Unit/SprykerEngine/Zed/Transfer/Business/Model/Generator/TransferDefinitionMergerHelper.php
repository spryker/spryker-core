<?php

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionMergerHelper
{

    /**
     * @return array
     */
    public function getTransferDefinition1()
    {
        return [
            'name' => 'Transfer',
            'bundles' => [
                'Bundle1',
            ],
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
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
            'bundles' => [
                'Bundle2',
            ],
            'property' => [
                [
                    'name' => 'propertyA',
                    'type' => 'string',
                ],
                [
                    'name' => 'propertyB',
                    'type' => 'int',
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
            'bundles' => [
                'Bundle1',
                'Bundle2',
            ],
            'property' => [
                'propertyA' => [
                    'name' => 'propertyA',
                    'type' => 'string',
                ],
                'propertyB' => [
                    'name' => 'propertyB',
                    'type' => 'int',
                ],
            ],
        ];
    }

}
