<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionNullMerger implements TransferDefinitionMergerInterface
{

    /**
     * @param array $transferDefinitions
     * @return array
     */
    public function merge(array $transferDefinitions)
    {
        return $transferDefinitions;
    }
}
