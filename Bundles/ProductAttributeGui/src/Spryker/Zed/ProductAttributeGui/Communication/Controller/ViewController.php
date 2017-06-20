<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiFacade getFacade()
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const PARAM_ID = 'id';
    const PARAM_SEARCH_TEXT = 'q';
    const PARAM_TERM = 'term';
    const PARAM_LOCALE_CODE = 'locale_code';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $attributes = $this
            ->getFacade()
            ->getAttributes($idProductAbstract);

        $values = $this
            ->getFacade()
            ->getProductAbstractAttributeValues($idProductAbstract);

        $values = $this->formatValues($attributes, $values);

        $locales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        return $this->viewResponse([
            'attributes' => $attributes,
            'locales' => $locales,
            'productAttributeValues' => $values,
        ]);
    }

    /**
     * @param array $attributeCollection
     * @param array $values
     *
     * @return array
     */
    protected function formatValues(array $attributeCollection, array $values)
    {
        $result = [];
        foreach ($values as $idLocale => $localizedData) {
            foreach ($localizedData as $key => $value) {
                $result[$idLocale][$key] = $value;

                $currentAttribute = $this->findAttributeByKey($attributeCollection, $key, $idLocale);
                if (!$currentAttribute) {
                    continue;
                }

                $translation = trim($currentAttribute['translation']);
                if ($translation) {
                    $result[$idLocale][$key] = $translation;
                }
            }
        }

        return $result;
    }

    /**
     * @param array $attributeCollection
     * @param $key
     * @param $idLocale
     *
     * @return null
     */
    protected function findAttributeByKey(array $attributeCollection, $key, $idLocale)
    {
        foreach ($attributeCollection as $attribute) {
            $attributeKey = $attribute['attribute_key'];
            $fkLocale = $attribute['fk_locale'];

            if ($attributeKey !== $key) {
                continue;
            }

            if ($idLocale === 'default') {
                $idLocale = null;
            }

            if ($idLocale !== null) {
                if ($fkLocale !== $idLocale) {
                    continue;
                }

                return $attribute;
            }

            return $attribute;
        }

        return null;
    }

}
