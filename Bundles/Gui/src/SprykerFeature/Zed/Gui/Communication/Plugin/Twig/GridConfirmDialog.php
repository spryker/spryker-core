<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class GridConfirmDialog extends TwigFunction
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
        return 'gridConfirmDialog';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($gridId, $buttonName, array $options = []) {
            $options = $this->addMessageHtml($options);

            return $this->getOutput($gridId, $buttonName, $options);
        };
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function addMessageHtml(array $options)
    {
        if (!array_key_exists('messages', $options)) {
            $options['messages'] = ['default' => $this->defaultMessage];
        }
        $messagesHtml = '';
        foreach ($options['messages'] as $type => $message) {
            if ($type === 'hints') {
                foreach ($message as $hint) {
                    $messagesHtml .= '<div class="hint">' . $hint . '</div>';
                }
            } else {
                $messagesHtml .= '<div>' . $message . '</div>';
            }
        }
        $options['message'] = $messagesHtml;

        return $options;
    }

    /**
     * @param $gridId
     * @param $buttonName
     * @param array $options
     *
     * @return string
     */
    public function getOutput($gridId, $buttonName, array $options)
    {
        $message = $options['message'];
        if (!array_key_exists('callback', $options)) {
            $callback = "$('#" . $gridId . "').data('kendoGrid').removeRow(confirmWindow.data('row')[0]);";
        } else {
            $callback = $options['callback'] . "(confirmWindow.data('row')[0]);";
        }
        if (array_key_exists('additionalCallback', $options)) {
            $callback .= $options['additionalCallback'] . ';';
        }
        $filter = new \Zend_Filter_Word_DashToCamelCase();
        $functionSuffix = $filter->filter($buttonName);

        return <<<HTML
<script>
    function confirmDialog{$functionSuffix}() {
        $('#{$gridId}').find('.k-grid-{$buttonName}').click(function() {
            $(
            '<div id="confirmWindow">' +
                '{$message}' +
                '<p>' +
                    '<input type="button" class="btn" id="btnYes" value="Yes" /> ' +
                    '<input type="button" class="btn" id="btnNo" value="No" />' +
                '</p>' +
            '</div>'
            ).appendTo(document.body);
            var confirmWindow = $('#confirmWindow');
            confirmWindow.data('row', $(this).parents('tr:first'));
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
                {$callback}
                confirmWindow.kendoWindow('close');
            });
        });
    }
</script>
<style>
    #confirmWindow {
        text-align: center;
    }
    #confirmWindow div.hint {
        font-size: smaller;
    }
    #confirmWindow .btn {
        margin: 8px 8px 0px 8px;
    }
</style>
HTML;
    }

}
