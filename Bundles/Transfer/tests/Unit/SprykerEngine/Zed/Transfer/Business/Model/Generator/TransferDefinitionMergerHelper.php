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
            'interface' => [
                ['name' => 'Path\To\Interface'],
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
            'interface' => [
                [
                    'name' => 'Path\To\Interface',
                ],
                [
                    'name' => 'Path\To\AnotherInterface',
                ],
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
            'interface' => [
                'Path\To\Interface' => [
                    'name' => 'Path\To\Interface',
                ],
                'Path\To\AnotherInterface' => [
                    'name' => 'Path\To\AnotherInterface',
                ],
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
