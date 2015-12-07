<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * @link http://code.google.com/intl/de-DE/apis/chart/image/docs/data_formats.html
 * @link https://developers.google.com/chart/image/docs/chart_params
 */

namespace SprykerFeature\Zed\Library\Service;

class GoogleGraph
{

    const URI = 'https://chart.googleapis.com/chart';

    protected $chf;
    protected $chtt;
    protected $cht;
    protected $chof;
    protected $chl;
    protected $chd;
    protected $chs;
    protected $chld;
    protected $chdl;
    protected $chdlp;
    protected $chdls;
    protected $chco;
    protected $chxl;
    protected $chxr;
    protected $chxt;
    protected $chds;
    protected $chls;
    protected $chg;
    protected $chm;
    protected $chxp;
    protected $chbh;

    public function request($sendHeader = true, $contentType = 'gif')
    {
        if (!in_array($contentType, ['gif', 'png', 'json'])) {
            throw new \Exception(sprintf('Content type "%s" is not a valid content type for this operation', $contentType));
        }

        $client = new \Zend_Http_Client();
        $client->setUri(self::URI);

        $vars = get_object_vars($this);
        foreach ($vars as $k => $v) {
            $client->setParameterPost($k, $v);
        }
        $response = $client->request('POST');
        if ($sendHeader) {
            header('content-type: image/' . $contentType);
        }

        return $response->getBody();
    }

    public function renderAsForm()
    {
        $vars = get_object_vars($this);
        $form = '<form action="https://chart.googleapis.com/chart" method="POST" >';
        foreach ($vars as $k => $v) {
            $form = $form . '<input type="hidden" name="' . $k .  '" value="' . $v . '"  />';
        }

        $form = $form . '<input type="submit"  /></form>';

        return $form;
    }

    /**
     * @return void
     */
    public function setChf($chf)
    {
        $this->chf = $chf;
    }

    /**
     * The chart title.
     *
     * @param $chl
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
     * @param $chl
     *
     * @return void
     */
    public function setChl($chl)
    {
        $this->chl = $chl;
    }

    /**
     * @return void
     */
    public function setChof($chof)
    {
        $this->chof = $chof;
    }

    /**
     * Chart type
     *
     * @param $cht
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
     * @param $chd
     *
     * @return void
     */
    public function setChd($chd)
    {
        $this->chd = $chd;
    }

    /**
     * @return void
     */
    public function setChs($chs)
    {
        $this->chs = $chs;
    }

    /**
     * Chart Label Data (various types)
     *
     * @param $chld
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
     * @param $chdl
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
     * @param $chdlp
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
     * @param $chdls
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
     * @param $chxl
     *
     * @return void
     */
    public function setChxl($chxl)
    {
        $this->chxl = $chxl;
    }

    /**
     * @return void
     */
    public function setChxt($chxt)
    {
        $this->chxt = $chxt;
    }

    /**
     * @return void
     */
    public function setChxr($chxr)
    {
        $this->chxr = $chxr;
    }

    /**
     * @return void
     */
    public function setChds($chds)
    {
        $this->chds = $chds;
    }

    /**
     * @return void
     */
    public function setChco($chco)
    {
        $this->chco = $chco;
    }

    /**
     * @return void
     */
    public function setChls($chls)
    {
        $this->chls = $chls;
    }

    /**
     * @return void
     */
    public function setChg($chg)
    {
        $this->chg = $chg;
    }

    /**
     * @return void
     */
    public function setChm($chm)
    {
        $this->chm = $chm;
    }

    /**
     * @return void
     */
    public function setChxp($chxp)
    {
        $this->chxp = $chxp;
    }

    /**
     * @return void
     */
    public function setChbh($chbh)
    {
        $this->chbh = $chbh;
    }

    public function toGetString()
    {
        $params = get_object_vars($this);
        $out = http_build_query($params);

        return self::URI . '?' . $out;
    }

}
