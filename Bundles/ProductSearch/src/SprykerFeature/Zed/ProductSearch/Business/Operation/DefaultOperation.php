<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Operation;

class DefaultOperation implements OperationInterface
{

    /**
     * @param array $sourceDocument
     * @param array $targetDocument
     * @param string $sourceField
     * @param string $targetField
     *
     * @return array
     */
    public function enrichDocument(array $sourceDocument, array $targetDocument, $sourceField, $targetField)
    {
        return $targetDocument;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Default';
    }

}
