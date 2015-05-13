<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface TransferDefinitionMergerInterface
{

    /**
     * @param array $transferDefinitions
     * @return array
     */
    public function merge(array $transferDefinitions);

}
