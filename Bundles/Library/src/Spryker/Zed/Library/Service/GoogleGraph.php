<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Service;

use Exception;
use Zend\Http\Client;
use Zend\Http\Request;

class GoogleGraph
{

    const URI = 'https://chart.googleapis.com/chart';

    /**
     * @var string
     */
    protected $chf;

    /**
     * @var string
     */
    protected $chtt;

    /**
     * @var string
     */
    protected $cht;

    /**
     * @var string
     */
    protected $chof;

    /**
     * @var string
     */
    protected $chl;

    /**
     * @var string
     */
    protected $chd;

    /**
     * @var string
     */
    protected $chs;

    /**
     * @var string
     */
    protected $chld;

    /**
     * @var string
     */
    protected $chdl;

    /**
     * @var string
     */
    protected $chdlp;

    /**
     * @var string
     */
    protected $chdls;

    /**
     * @var string
     */
    protected $chco;

    /**
     * @var string
     */
    protected $chxl;

    /**
     * @var string
     */
    protected $chxr;

    /**
     * @var string
     */
    protected $chxt;

    /**
     * @var string
     */
    protected $chds;

    /**
     * @var string
     */
    protected $chls;

    /**
     * @var string
     */
    protected $chg;

    /**
     * @var string
     */
    protected $chm;

    /**
     * @var string
     */
    protected $chxp;

    /**
     * @var string
     */
    protected $chbh;

    /**
     * @param bool $sendHeader
     * @param string $contentType
     *
     * @throws \Exception
     * @return string
     */
    public function request($sendHeader = true, $contentType = 'gif')
    {
        if (!in_array($contentType, ['gif', 'png', 'json'])) {
            throw new Exception(sprintf('Content type "%s" is not a valid content type for this operation', $contentType));
        }

        $client = new Client();
        $client->setUri(self::URI);

        $vars = get_object_vars($this);
        $client->setParameterPost($vars);

        $client->setMethod(Request::METHOD_POST);
        $response = $client->send();
        if ($sendHeader) {
            header('content-type: image/' . $contentType);
        }

        return $response->getBody();
    }

    /**
     * @return string
     */
    public function renderAsForm()
    {
        $vars = get_object_vars($this);
        $form = '<form action="https://chart.googleapis.com/chart" method="POST" >';
        foreach ($vars as $k => $v) {
            $form = $form . '<input type="hidden" name="' . $k . '" value="' . $v . '"  />';
        }

        $form = $form . '<input type="submit"  /></form>';

        return $form;
    }

    /**
     * @param string $chf
     *
     * @return void
     */
    public function setChf($chf)
    {
        $this->chf = $chf;
    }

    /**
     * The chart title.
     *
     * @param string $chtt
     *
     * @return void
     */
    public function setChtt($chtt)
    {
        $this->chtt = $chtt;
    }

    /**
     * The slice labels.
     *
     * @param string $chl
     *
     * @return void
     */
    public function setChl($chl)
    {
        $this->chl = $chl;
    }

    /**
     * @param string $chof
     *
     * @return void
     */
    public function setChof($chof)
    {
        $this->chof = $chof;
    }

    /**
     * Chart type
     *
     * @param string $cht
     *
     * @return void
     */
    public function setCht($cht)
    {
        $this->cht = $cht;
    }

    /**
     * The chart data.
     *
     * @param string $chd
     *
     * @return void
     */
    public function setChd($chd)
    {
        $this->chd = $chd;
    }

    /**
     * @param string $chs
     *
     * @return void
     */
    public function setChs($chs)
    {
        $this->chs = $chs;
    }

    /**
     * Chart Label Data (various types)
     *
     * @param string $chld
     *
     * @return void
     */
    public function setChld($chld)
    {
        $this->chld = $chld;
    }

    /**
     * Chart legend text and style
     *
     * @link https://developers.google.com/chart/image/docs/chart_params#gcharts_legend
     *
     * @param string $chdl
     *
     * @return void
     */
    public function setChdl($chdl)
    {
        $this->chdl = $chdl;
    }

    /**
     * Chart legend text and style
     *
     * @link https://developers.google.com/chart/image/docs/chart_params#gcharts_legend
     *
     * @param string $chdlp
     *
     * @return void
     */
    public function setChdlp($chdlp)
    {
        $this->chdlp = $chdlp;
    }

    /**
     * Chart legend text and style
     *
     * @link https://developers.google.com/chart/image/docs/chart_params#gcharts_legend
     *
     * @param string $chdls
     *
     * @return void
     */
    public function setChdls($chdls)
    {
        $this->chdls = $chdls;
    }

    /**
     * Custom Axis Labels
     *
     * @param string $chxl
     *
     * @return void
     */
    public function setChxl($chxl)
    {
        $this->chxl = $chxl;
    }

    /**
     * @param string $chxt
     *
     * @return void
     */
    public function setChxt($chxt)
    {
        $this->chxt = $chxt;
    }

    /**
     * @param string $chxr
     *
     * @return void
     */
    public function setChxr($chxr)
    {
        $this->chxr = $chxr;
    }

    /**
     * @param string $chds
     *
     * @return void
     */
    public function setChds($chds)
    {
        $this->chds = $chds;
    }

    /**
     * @param string $chco
     *
     * @return void
     */
    public function setChco($chco)
    {
        $this->chco = $chco;
    }

    /**
     * @param string $chls
     *
     * @return void
     */
    public function setChls($chls)
    {
        $this->chls = $chls;
    }

    /**
     * @param string $chg
     *
     * @return void
     */
    public function setChg($chg)
    {
        $this->chg = $chg;
    }

    /**
     * @param string $chm
     *
     * @return void
     */
    public function setChm($chm)
    {
        $this->chm = $chm;
    }

    /**
     * @param string $chxp
     *
     * @return void
     */
    public function setChxp($chxp)
    {
        $this->chxp = $chxp;
    }

    /**
     * @param string $chbh
     *
     * @return void
     */
    public function setChbh($chbh)
    {
        $this->chbh = $chbh;
    }

    /**
     * @return string
     */
    public function toQueryString()
    {
        $params = get_object_vars($this);
        $out = http_build_query($params);

        return self::URI . '?' . $out;
    }

}
