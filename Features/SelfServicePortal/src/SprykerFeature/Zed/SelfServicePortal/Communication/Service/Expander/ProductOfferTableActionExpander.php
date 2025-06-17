<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Service\UtilText\Model\Url\Url;

class ProductOfferTableActionExpander implements ProductOfferTableActionExpanderInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_PRODUCT_OFFER = 'id_product_offer';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @param array<string, mixed> $rowData
     * @param array<string, mixed> $productOfferData
     *
     * @return array<int|string, mixed>
     */
    public function expandData(array $rowData, array $productOfferData): array
    {
        if (!isset($rowData[static::COL_ACTIONS])) {
            return $rowData;
        }

        $rowData[static::COL_ACTIONS] .= ' ' . $this->generateEditButton(
            Url::generate(
                '/self-service-portal/edit-offer',
                [
                    static::REQUEST_PARAM_ID_PRODUCT_OFFER => $productOfferData[SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER],
                ],
            ),
            'Edit',
            ['icon' => 'fa fa fa-pencil', 'class' => 'btn-info btn-xs'],
        );

        return $rowData;
    }

    /**
     * @param string $url
     * @param string $title
     * @param array<string, string> $options
     *
     * @return string
     */
    protected function generateEditButton(string $url, string $title, array $options = []): string
    {
        $defaultOptions = [
            'class' => 'btn-default',
            'icon' => '',
        ];

        $options = array_merge($defaultOptions, $options);
        $buttonClass = $options['class'];
        $iconClass = $options['icon'];

        return sprintf(
            '<a class="btn btn-sm %s" href="%s" title="%s">%s %s</a>',
            $buttonClass,
            $url,
            $title,
            $iconClass ? '<i class="' . $iconClass . '"></i>' : '',
            $title,
        );
    }
}
