<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class ConfirmDialog extends TwigFunction
{

    /**
     * @var string
     */
    protected $defaultMessage = 'Do you really want to delete this?';

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'confirmDialog';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function () {
            $output = $this->getOutput();

            return $output;
        };
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return <<<HTML
<script>
    function confirmDialog(message, callback, args) {
        $(
        '<div class="confirmDialog">' +
            message +
            '<p>' +
                '<input type="button" class="btn" id="btnYes" value="Yes" /> ' +
                '<input type="button" class="btn" id="btnNo" value="No" />' +
            '</p>' +
        '</div>'
        ).appendTo(document.body);

        var confirmWindow = $('.confirmDialog');
        confirmWindow.kendoWindow({
            modal: true,
            width: '300px',
            height: 'auto',
            visible: false,
            actions: ['Close'],
            close: function() {
                setTimeout(function() {
                    confirmWindow.kendoWindow('destroy');
                }, 200);
            }
        }).data('kendoWindow').center().open();

        confirmWindow.find('#btnNo').click(function() {
            confirmWindow.kendoWindow('close');
        });

        confirmWindow.find('#btnYes').click(function() {
            callback(args);
            confirmWindow.kendoWindow('close');
        });
    }
</script>
<style>
    .confirmDialog {
        text-align: center;
    }
    .confirmDialog div.hint {
        font-size: smaller;
    }
    .confirmDialog .btn {
        margin: 8px 8px 0px 8px;
    }
</style>
HTML;
    }

}
