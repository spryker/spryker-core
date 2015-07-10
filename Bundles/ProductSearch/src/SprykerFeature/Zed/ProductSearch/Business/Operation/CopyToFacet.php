<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Operation;

class CopyToFacet implements OperationInterface
{

    const FACET_NAME_FIELD = 'facet-name';
    const FACET_VALUE_FIELD = 'facet-value';

    /**
     * @param array     $sourceDocument
     * @param array     $targetDocument
     * @param mixed     $sourceField
     * @param string    $targetField
     *
     * @return array
     */
    public function enrichDocument(array $sourceDocument, array $targetDocument, $sourceField, $targetField)
    {
        if (isset($sourceDocument[$sourceField]) && !empty($sourceDocument[$sourceField])) {
            $facet = [
                self::FACET_NAME_FIELD => $sourceField,
                self::FACET_VALUE_FIELD => $sourceDocument[$sourceField],
            ];
            $targetDocument[$targetField][] = $facet;
        }

        return $targetDocument;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CopyToFacet';
    }

}
