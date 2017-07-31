# yii2-dynamictabs

动态创建tabs选项卡, 兼容yii2原本tabs, 不支持Dropdown

## 安装
首先安装 [composer](http://getcomposer.org/download/)

运行
```sh
$ php composer.phar require --prefer-dist yesela/yii2-dynamictabs "@dev"
```
或者增加

```
"yesela/yii2-dynamictabs": "@dev"
```

到composer.json文件中

## 使用方法

首先你要先确定是否引入了[jquery-ui](https://github.com/jquery/jquery-ui), 没有的话, 请先引入该JS

### view
在view模板中, 增加
```php
use yesela\dynamictabs\DynamicTabs;
echo DynamicTabs::widget([
    'containerOptions' => ['id' => 'tabs-here'],
    'options' => ['id'=> 'my-tabs'],
    'items' => [
    [
        'label' => 'one',
	    'content' => "content 1",
	    'active' => true,
	    //'linkOptions'=>['data-closable'=>'true','data-url'=>'http://www.example.com']
	],
	[
	    'label' => 'two ',
	    'content' => 'content 2',
	    'options' => ['id' => 'id1'],
	],
	[
	    'label' => 'three',
	    'linkOptions'=>['data-url'=>'http://www.example.com']
	],
    ],
]);

```
#### 属性说明

##### `data-closable`
该tab能否关闭

#### `data-url`
该tab内容的url, 有该属性时, 不显示content内容


### 链接
在所在链接中增加属性```'data-toggle'=>'tabajax'```
支持以下两种情况
```php
//id, data-url, data-title是必须的选项, data-css以及data-js可选, 当有span时, tabs获取该class作为icon
Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', ['id' => 'tabs-id-1',  'data-title' => 'title','data-url'=>'http://www.example.com', 'data-toggle' => 'tabajax', 'data-css'=>['css1.css','css2.css'], 'data-js'=>['js1.js','js2.js']]);
```
或
```php
//一般用于Menu::widget, 获取的tabs标题为查找span的文字, tabs的URL为a的href, 当有i时, tabs获取该class作为icon
<li id="tabs-id-2" data-toggle="tabajax"><a href="http://www.example.com"><i class="fa fa-angle-double-right"></i><span>测试</span></a></li>
```