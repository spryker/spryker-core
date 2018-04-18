<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class LocalizedAttributesExtractorStep implements DataImportStepInterface
{
    const KEY_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    /**
     * @var array
     */
    protected $attributeNames;

    /**
     * @param array $attributeNames
     */
    public function __construct(array $attributeNames)
    {
        $this->attributeNames = $attributeNames;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $localizedAttributes = [];
        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $attributes = [];

            foreach ($this->attributeNames as $attributeName) {
                $attributes[$attributeName] = $dataSet[$attributeName . '.' . $localeName];
            }

            $localizedAttributes[$idLocale] = $attributes;
        }

        $dataSet[static::KEY_LOCALIZED_ATTRIBUTES] = $localizedAttributes;
    }
}
