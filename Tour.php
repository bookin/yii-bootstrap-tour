<?php

/**
 * Class Tour
 *
 * @property array $options the options for the underlying Bootstrap Tour JS plugin.
 * Please refer to the corresponding [Bootstrap Tour plugin API](http://bootstraptour.com/api/) for possible options
 */
class Tour extends CWidget
{
    const START_MODE_NONE = 0; //Don't initialize or start the tour
    const START_MODE_INIT_ONLY = 1; //Only initialize the tour
    const START_MODE_DEFAULT = 2; //Initialize and start the tour
    const START_MODE_FORCE_START = 3; //Initialize and force start the tour

    /**
     * @var string $name The javascript variable name used for the tour
     */
    public $name = 'tour';

    /**
     * @var string $scope The scope used for the javascript variable, e.g. 'window'. Leave blank for local scope.
     */
    public $scope;

    /**
     * @var int Start of the tour
     */
    public $startMode = self::START_MODE_DEFAULT;

    /**
     * @var array the options for the underlying Bootstrap Tour JS plugin.
     * Please refer to the corresponding [Bootstrap Tour plugin API](http://bootstraptour.com/api/) for possible options.
     */
    public $options = [];

    protected $defaultOptions = [
        'template'=>"js:function(i, step){
            var html = $(\"\
                <div class='popover tour'>\
                    <div class='arrow'></div>\
                    <h3 class='header'>\
                        <a class='close pull-right' data-role='end'>\
                            <span class='glyphicon glyphicon-remove' aria-hidden='true'></span>\
                        </a>\
                    </h3>\
                    <div class='popover-content'></div>\
                    <div class='popover-navigation'>\
                        <a class='btn btn-link' data-role='prev'>&#171; Prev</a>\
                        <a class='btn btn-link' data-role='next'>Next &#187;</a>\
                    </div>\
                </div>\
            \");
            html.find('.header').prepend(step.title).end().children('.popover-content').prepend(step.content);
            return html;
        }"
    ];

    private $assetsDir;

    public function init(){
        parent::init();
        $dir = dirname(__FILE__) . '/assets';
        /** @var CAssetManager $manager */
        $manager = Yii::app()->assetManager;
        $this->assetsDir = $manager->publish($dir, false, -1, YII_DEBUG);
    }

    public function run(){
        if ($this->options !== false) {
            $this->registerScripts();

            $this->defaultOptions['name']=$this->name;
            $options = array_merge($this->defaultOptions, $this->options);
            $options = empty($options) ? '' : CJavaScript::encode($options);

            $varName = $this->getVarName();
            $js = "$varName = new Tour($options);\n";

            if ($this->startMode >= self::START_MODE_INIT_ONLY)
                $js .= "$varName.init();\n";
            if ($this->startMode >= self::START_MODE_DEFAULT) {
                $forced = $this->startMode >= self::START_MODE_FORCE_START ? 'true' : '';
                $js .= "$varName.start($forced);\n";
            }

            Yii::app()->clientScript->registerScript($this->id, $js);
        }
    }

    public function getVarName(){
        return $this->scope ? $this->scope . '.' . $this->name : $this->name;
    }

    /**
     * Register client scripts
     * @throws CException
     */
    private function registerScripts()
    {
        /** @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        if(YII_DEBUG){
            $cs->registerScriptFile($this->assetsDir . '/js/bootstrap-tour.js', CClientScript::POS_END);
            $cs->registerCssFile($this->assetsDir . '/css/bootstrap-tour.css');
        }else{
            $cs->registerScriptFile($this->assetsDir . '/js/bootstrap-tour.min.js', CClientScript::POS_END);
            $cs->registerCssFile($this->assetsDir . '/css/bootstrap-tour.min.css');
        }
        $cs->registerCssFile($this->assetsDir . '/css/custom.css');

    }
}