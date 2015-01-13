<?php
/**
 * @link http://www.creators.zp.ua/yii2-metroui
 * @copyright Copyright (c) 2015 Remchi <creators@email.ua>
 * @license BSD-3-Clause
 */

namespace creators\metroui;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * NavBar renders a navbar HTML component.
 *
 * Any content enclosed between the [[begin()]] and [[end()]] calls of NavBar
 * is treated as the content of the navbar. You may use widgets such as [[Menu]]
 * or [[\creators\metroui\Menu]] to build up such content. For example,
 *
 * ```php
 * use creators\metroui\NavBar;
 * use creators\metroui\Menu;
 *
 * NavBar::begin(['brandLabel' => 'NavBar Test']);
 * echo Menu::widget([
 *     'items' => [
 *         ['label' => 'Home', 'url' => ['/site/index']],
 *         ['label' => 'About', 'url' => ['/site/about']],
 *     ],
 * ]);
 * NavBar::end();
 * ```
 *
 * @see http://metroui.org.ua/navbar.html
 * @author Remchi <creators@email.ua>
 * @since 1.0
 */
class NavBar extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "div", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var string|boolean the text of the brand of false if it's not used. Note that this is not HTML-encoded.
     * @see http://metroui.org.ua/navbar.html
     */
    public $brandLabel = false;
    /**
     * @param array|string|boolean $url the URL for the brand's hyperlink tag. This parameter will be processed by [[Url::to()]]
     * and will be used for the "href" attribute of the brand link. Default value is false that means
     * [[\yii\web\Application::homeUrl]] will be used.
     */
    public $brandUrl = false;
    /**
     * @var array the HTML attributes of the brand link.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $brandOptions = [];
    /**
     * @var array the HTML attributes of the inner container.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $innerContainerOptions = [];


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->clientOptions = false;

        Html::addCssClass($this->options, 'navigation-bar');
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);

        Html::addCssClass($this->innerContainerOptions, 'navigation-bar-content');
        //Html::addCssClass($this->innerContainerOptions, 'container');
        echo Html::beginTag('div', $this->innerContainerOptions);

        if ($this->brandLabel !== false) {
            Html::addCssClass($this->brandOptions, 'element');
            echo Html::a($this->brandLabel, $this->brandUrl === false ? Yii::$app->homeUrl : $this->brandUrl, $this->brandOptions);
            echo Html::tag('span', '', ['class' => 'element-divider']);
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::endTag('div');

        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        echo Html::endTag($tag, $this->options);

        MetroUiPluginAsset::register($this->getView());
    }
}
