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
                'property' => $this->normalizeAttribute($transferDefinition['property']),
            ];

            if (array_key_exists('interface', $transferDefinition)) {
                $normalizedDefinition['interface'] = $this->normalizeAttribute($transferDefinition['interface']);
            }

            $normalizedDefinitions[] = $normalizedDefinition;
        }

        return $normalizedDefinitions;
    }

    /**
     * @param array $attribute
     *
     * @return array
     */
    private function normalizeAttribute(array $attribute)
    {
        if (isset($attribute[0])) {
            return $attribute;
        }

        return [$attribute];
    }
}
