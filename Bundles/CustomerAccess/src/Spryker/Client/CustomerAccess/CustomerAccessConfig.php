<?php

namespace Spryker\Client\CustomerAccess;

class CustomerAccessConfig
{
    /**
     * Convention is SEE_{content type}_PLUGIN for constant name and the value is the key that would be used as a
     * permission key can('SeePrice') as an example
     */
    const SEE_PRICE_PLUGIN = 'SeePrice';

    /**
     * @param string $contentType
     *
     * @return string
     */
    public function getPluginNameToSeeContentType($contentType)
    {
        $constantName = $this->getConstantNameFromContentType($contentType);

        if(defined(CustomerAccessConfig::class . '::' . $constantName)) {
            return constant(CustomerAccessConfig::class . '::' . $constantName);
        }

        return '';
    }

    /**
     * @param $contentType
     *
     * @return string
     */
    protected function getConstantNameFromContentType($contentType)
    {
        return 'SEE_' . strtoupper($contentType) . '_PLUGIN';
    }
}
