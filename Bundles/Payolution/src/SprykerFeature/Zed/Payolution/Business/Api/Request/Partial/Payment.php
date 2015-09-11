<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;

use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequestExporter;

class Payment extends AbstractRequestExporter
{

    const CODE_PRE_AUTHORIZATION = 'VA.PA';
    const CODE_RE_AUTHORIZATION = self::CODE_PRE_AUTHORIZATION;
    const CODE_REVERSAL = 'VA.RV';
    const CODE_CAPTURE = 'VA.CP';
    const CODE_REFUND = 'VA.RF';

    /**
     * @var string
     */
    protected $code;

    /**
     * @var Presentation;
     */
    protected $presentation;

    public function __construct()
    {
        $this->setPresentation(new Presentation());
    }


    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Presentation
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param Presentation $presentation
     */
    public function setPresentation(Presentation $presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * @return array
     */
    protected function getXmlAttributeProperties()
    {
        return ['code'];
    }

}
