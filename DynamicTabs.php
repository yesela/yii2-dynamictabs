<?php
/**
 * @Copyright Copyright (c) 2017 @DynamicTabs.php By Kami
 * @License http://www.yuzhai.tv/
 */

namespace yesela\dynamictabs;

use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\bootstrap\Dropdown;
use yii\web\View;

class DynamicTabs extends Tabs
{
    /**
     * @var array 设置tabs容器的一些选项
     */
    public $containerOptions = ['id' => 'kami-tabs'];

    public function run()
    {
        $this->registerPlugin('tab');
        $this->registerAssets();
        $this->registerScript();
        echo $this->renderItems();
    }

    protected function renderItems()
    {
        $headers = [];
        $panes = [];
        if (!$this->hasActiveTab() && !empty($this->items)) {
            $this->items[0]['active'] = true;
        }
        foreach ($this->items as $n => $item) {
            if (!ArrayHelper::remove($item, 'visible', true)) {
                continue;
            }
            if (!array_key_exists('label', $item)) {
                throw new InvalidConfigException("The 'label' option is required.");
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $headerOptions = array_merge($this->headerOptions, ArrayHelper::getValue($item, 'headerOptions', []));
            $linkOptions = array_merge($this->linkOptions, ArrayHelper::getValue($item, 'linkOptions', []));
            if (isset($item['items'])) {
                $label .= ' <b class="caret"></b>';
                Html::addCssClass($headerOptions, ['widget' => 'dropdown']);
                if ($this->renderDropdown($n, $item['items'], $panes)) {
                    Html::addCssClass($headerOptions, 'active');
                }
                Html::addCssClass($linkOptions, ['widget' => 'dropdown-toggle']);
                if (!isset($linkOptions['data-toggle'])) {
                    $linkOptions['data-toggle'] = 'dropdown';
                }
                $header = Html::a($label, "#", $linkOptions) . "\n"
                    . Dropdown::widget(['items' => $item['items'], 'clientOptions' => false, 'view' => $this->getView()]);
            } else {
                $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
                $options['id'] = ArrayHelper::getValue($options, 'id', $this->options['id'] . '-tab' . $n);
                Html::addCssClass($options, ['widget' => 'tab-pane']);
                if (ArrayHelper::remove($item, 'active')) {
                    Html::addCssClass($options, 'active');
                    Html::addCssClass($headerOptions, 'active');
                }
                if (isset($item['url'])) {
                    $header = Html::a($label, $item['url'], $linkOptions);
                } else {
                    if (!isset($linkOptions['data-toggle'])) {
                        $linkOptions['data-toggle'] = 'tab';
                    }
                    if (isset($linkOptions['data-closable'])) {
                        $label =  Html::button('x', ['class' => 'close']) . $label;
                    }
                    $header = Html::a($label, '#' . $options['id'], $linkOptions);
                }
                if ($this->renderTabContent) {
                    $tag = ArrayHelper::remove($options, 'tag', 'div');
                    $panes[] = Html::tag($tag, isset($item['content']) ? $item['content'] : '', $options);
                }
            }
            $headers[] = Html::tag('li', $header, $headerOptions);
        }
        return Html::tag('div',
            Html::tag('ul', implode("\n", $headers), $this->options)
            . ($this->renderTabContent ? "\n" . Html::tag('div', implode("\n", $panes), ['class' => 'tab-content']) : ''),
            $this->containerOptions);
    }

    public function registerAssets()
    {
        $view = $this->getView();
        DynamicTabsAssets::register($view);
    }

    public function registerScript()
    {
        $view = $this->getView();
        $id = $this->options['id'];
        $js = <<<JS
var tabs = $('#{$this->containerOptions['id']}').bootstrapDynamicTabsClose();
$('[data-toggle="tabajax"]').click(function(e) {
    e.preventDefault();
    var loadUrl = $(this).find('a').attr('href'),
        loadIcon = $(this).find('i').attr('class');
        loadTitle = $(this).find('span').text();
    if (typeof loadUrl === 'undefined') {
        loadUrl = $(this).data('url');
        loadTitle = $(this).data('title');
        loadIcon = $(this).find('span').attr('class');
    }
    tabs.addTab({
        title: loadTitle,
        id: $(this).attr('id'),
        ajaxUrl: loadUrl,
        loadScripts: $(this).data('js'),
		loadStyles: $(this).data('css'),
		icon: loadIcon
    });
});
$('#{$id}').bootstrapDynamicTabs();
JS;
        $view->registerJs($js ,View::POS_END);
    }
}