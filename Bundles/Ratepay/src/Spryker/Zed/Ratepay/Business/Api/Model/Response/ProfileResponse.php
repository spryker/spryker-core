<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Response;

use Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement;

class ProfileResponse extends BaseResponse
{
    /**
     * @return array
     */
    public function getMasterData()
    {
        return $this->simpleXmlElementToArrayRecursive($this->xmlObject->content->{'master-data'});
    }

    /**
     * @return array
     */
    public function getInstallmentConfigurationResult()
    {
        return $this->simpleXmlElementToArrayRecursive($this->xmlObject->content->{'installment-configuration-result'});
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement $xmlObject
     *
     * @return array
     */
    protected function simpleXmlElementToArrayRecursive(SimpleXMLElement $xmlObject)
    {
        return json_decode(json_encode((array)$xmlObject), true);
    }
}
