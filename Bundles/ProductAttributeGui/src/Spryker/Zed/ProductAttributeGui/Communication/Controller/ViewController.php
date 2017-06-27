<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
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

        $dataProvider = $this->getFactory()->createAttributeKeyFormDataProvider();
        $form = $this
            ->getFactory()
            ->createAttributeKeyForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $values = $this
            ->getFacade()
            ->getProductAbstractAttributeValues($idProductAbstract);

        $metaAttributes = $this
            ->getFacade()
            ->getMetaAttributes($idProductAbstract);

        $locales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale(null);
        $localeTransfer->setLocaleName('_');

        $locales['_'] = $localeTransfer;

        ksort($locales);
        ksort($values);

        return $this->viewResponse([
            'attributeKeyForm' => $form->createView(),
            'locales' => $locales,
            'metaAttributes' => $metaAttributes,
            'productAttributeValues' => $values,
            'localesJson' => json_encode(array_keys($locales)),
            'productAttributeValuesJson' => json_encode($values),
            'metaAttributesJson' => json_encode($metaAttributes),

        ]);
    }

}
