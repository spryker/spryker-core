<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Specification\MetaData;

use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleWithAttributesPluginInterface;

class MetaDataProvider implements MetaDataProviderInterface
{
    /**
     * @var array<string, list<string>>|null
     */
    protected ?array $availableFieldsBuffer = null;

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $availableFieldsMapBuffer = null;

    /**
     * @var array<string, list<string>>
     */
    protected static array $attributeFieldsBuffer;

    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface> $rulePlugins
     * @param string $field
     *
     * @return bool
     */
    public function isFieldAvailable(array $rulePlugins, string $field): bool
    {
        $arrayIdentifier = $this->getArrayIdentifier($rulePlugins);
        if (!isset($this->availableFieldsMapBuffer[$arrayIdentifier])) {
            $this->loadAvailableFieldsBuffers($rulePlugins, $arrayIdentifier);
        }

        return isset($this->availableFieldsMapBuffer[$arrayIdentifier][$field]);
    }

    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface> $rulePlugins
     * @param string $arrayIdentifier
     *
     * @return void
     */
    protected function loadAvailableFieldsBuffers(array $rulePlugins, string $arrayIdentifier): void
    {
        $queryStringFields = [];
        foreach ($rulePlugins as $rulePlugin) {
            if ($rulePlugin instanceof RuleWithAttributesPluginInterface) {
                $queryStringFields[] = $this->getAttributeTypes($rulePlugin);

                continue;
            }

            $queryStringFields[] = [$rulePlugin->getFieldName()];
        }

        $queryStringFields = array_merge(...$queryStringFields);
        $this->availableFieldsBuffer[$arrayIdentifier] = $queryStringFields;
        $this->availableFieldsMapBuffer[$arrayIdentifier] = array_flip($queryStringFields);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleWithAttributesPluginInterface&\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface $rulePlugin
     *
     * @return list<string>
     */
    protected function getAttributeTypes(RulePluginInterface&RuleWithAttributesPluginInterface $rulePlugin): array
    {
        $pluginClassName = get_class($rulePlugin);
        if (isset(static::$attributeFieldsBuffer[$pluginClassName])) {
            return static::$attributeFieldsBuffer[$pluginClassName];
        }

        static::$attributeFieldsBuffer[$pluginClassName] = [];
        foreach ($rulePlugin->getAttributeTypes() as $attributeType) {
            static::$attributeFieldsBuffer[$pluginClassName][] = $rulePlugin->getFieldName() . '.' . $attributeType;
        }

        return static::$attributeFieldsBuffer[$pluginClassName];
    }

    /**
     * @param list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RulePluginInterface> $rulePlugins
     *
     * @return string
     */
    protected function getArrayIdentifier(array $rulePlugins): string
    {
        return md5(json_encode($rulePlugins, JSON_THROW_ON_ERROR));
    }
}
