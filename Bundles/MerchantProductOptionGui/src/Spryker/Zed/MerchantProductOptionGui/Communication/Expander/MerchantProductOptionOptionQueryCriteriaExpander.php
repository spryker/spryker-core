<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\MerchantProductOptionGui\Persistence\MerchantProductOptionGuiRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantProductOptionOptionQueryCriteriaExpander implements MerchantProductOptionQueryCriteriaExpanderInterface
{
    /**
     * @var string
     */
    protected const URL_PARAM_ID_MERCHANT = 'id-merchant';

    /**
     * @var \Spryker\Zed\MerchantProductOptionGui\Persistence\MerchantProductOptionGuiRepositoryInterface
     */
    protected $merchantProductOptionGuiRepository;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    protected $request;

    /**
     * @param \Spryker\Zed\MerchantProductOptionGui\Persistence\MerchantProductOptionGuiRepositoryInterface $merchantProductOptionGuiRepository
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     */
    public function __construct(
        MerchantProductOptionGuiRepositoryInterface $merchantProductOptionGuiRepository,
        ?Request $request
    ) {
        $this->merchantProductOptionGuiRepository = $merchantProductOptionGuiRepository;
        $this->request = $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        if (!$this->request) {
            return $queryCriteriaTransfer;
        }

        $idMerchant = $this->request->get(static::URL_PARAM_ID_MERCHANT);

        if (!$idMerchant) {
            return $queryCriteriaTransfer;
        }

        return $this->merchantProductOptionGuiRepository->expandQueryCriteriaTransferWithMerchantProductOptionRelation(
            $queryCriteriaTransfer,
            (new MerchantProductOptionGroupCriteriaTransfer())->setIdMerchant($idMerchant),
        );
    }
}
