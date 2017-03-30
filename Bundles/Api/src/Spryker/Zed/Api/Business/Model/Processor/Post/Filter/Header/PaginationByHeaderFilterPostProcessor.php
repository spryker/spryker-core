<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Filter\Header;

use Generated\Shared\Transfer\ApiMetaTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

class PaginationByHeaderFilterPostProcessor implements PostProcessorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        $headers = $apiRequestTransfer->getHeaderData();
        if (empty($headers['range'])) {
            return $apiResponseTransfer;
        }

        $pagination = $apiResponseTransfer->getPagination();
        // FIXME: FOR NOW (we need to map the find result into pagination object for returning!
        if (!$pagination) {
            $pagination = $apiRequestTransfer->getFilter()->getPagination();
        }

        if (!$pagination) {
            return $apiResponseTransfer;
        }

        if ($pagination->getOffset() && $pagination->getLimit()) {
            $headers = $apiResponseTransfer->getHeaders();
            $headers['Accept-Ranges'] = $apiRequestTransfer->getResource();

            $from = $pagination->getOffset();
            $to = $pagination->getOffset() + $pagination->getLimit();
            $total = $pagination->getTotal();
            $headers['Content-Range'] = $apiRequestTransfer->getResource() . ' ' . $from . '-' . $to . '/' . $total;
            $apiResponseTransfer->setHeaders($headers);
        }

        // Return "Partial Content" as the result doesn't fit on a single page.
        if ($pagination->getPages() > 1) {
            $apiResponseTransfer->setCode(206);
        }

        // Add pagination to Meta links
        //FIXME
        $metaTransfer = $apiResponseTransfer->getMeta();
        if (!$metaTransfer) {
            $metaTransfer = new ApiMetaTransfer();
            $apiResponseTransfer->setMeta($metaTransfer);
        }
        $links = $metaTransfer->getLinks();

        $links['first'] = $pagination->getFirst();
        $links['prev'] = $pagination->getPrev();
        $links['next'] = $pagination->getNext();
        $links['last'] = $pagination->getLast();
        $links['page'] = $pagination->getPage();
        $links['pages'] = $pagination->getPages();
        $apiResponseTransfer->getMeta()->setLinks($links);

        return $apiResponseTransfer;
    }

}
