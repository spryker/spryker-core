<?php

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


            // @TODO this code block can be removed when all <interface> elements of the xml are removed [
            $internalInterface = [
                'name' => 'Generated\\Shared\\' . $transferDefinition['bundle'] . '\\' . $transferDefinition['name'] . 'Interface',
                'bundle' => $transferDefinition['bundle']
            ];

            if (array_key_exists('interface', $transferDefinition)) {
                $normalizedDefinition['interface'] = $this->normalizeAttributes($transferDefinition['interface'], $transferDefinition['bundle']);
                $normalizedDefinition['interface'][] = $internalInterface;
            } else {
                $normalizedDefinition['interface'] = [$internalInterface];
            }
            // ]

            // @TODO this code block must be uncommented
//            $normalizedDefinition['interface'] = [
//                [
//                    'name' => 'Generated\\Shared\\' . $transferDefinition['bundle'] . '\\' . $transferDefinition['name'] . 'Interface',
//                    'bundle' => $transferDefinition['bundle']
//                ]
//            ];

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
