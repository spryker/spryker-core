<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class DefinitionNormalizer
{

    /**
     * @param array $transferDefinitions
     *
     * @return array
     */
    public function normalizeDefinitions(array $transferDefinitions)
    {
        $normalizedDefinitions = [];
        foreach ($transferDefinitions as $transferDefinition) {
            $normalizedDefinition = [
                'bundle' => $transferDefinition['bundle'],
                'name' => $transferDefinition['name'],
                'property' => $this->normalizeAttributes($transferDefinition['property'], $transferDefinition['bundle']),
            ];

            $normalizedDefinition['interface'] = [
                [
                    'name' => 'Generated\\Shared\\' . $transferDefinition['bundle'] . '\\' . $transferDefinition['name'] . 'Interface',
                    'bundle' => $transferDefinition['bundle'],
                ],
            ];

            $normalizedDefinitions[] = $normalizedDefinition;
        }

        return $normalizedDefinitions;
    }

    /**
     * @param array $attributes
     * @param string $bundle
     *
     * @return array
     */
    private function normalizeAttributes(array $attributes, $bundle)
    {
        if (isset($attributes[0])) {
            return $this->addBundleToAttributes($attributes, $bundle);
        }

        return $this->addBundleToAttributes([$attributes], $bundle);
    }

    /**
     * @param array $attributes
     * @param string $bundle
     *
     * @return array
     */
    private function addBundleToAttributes(array $attributes, $bundle)
    {
        foreach ($attributes as &$attribute) {
            $attribute['bundle'] = $bundle;
        }

        return $attributes;
    }

}
