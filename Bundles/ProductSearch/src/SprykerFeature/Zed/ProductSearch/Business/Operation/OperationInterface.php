<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Operation;

interface OperationInterface
{

    /**
     * @param array $sourceDocument
     * @param array $targetDocument
     * @param mixed $sourceField
     * @param string $targetField
     *
     * @return array
     */
    public function enrichDocument(array $sourceDocument, array $targetDocument, $sourceField, $targetField);

    /**
     * @return string
     */
    public function getName();

}
