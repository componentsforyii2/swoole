<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/27/17
 * Time: 3:58 PM
 */

namespace componentsforyii2\swoole\console;
use yii\db\BaseActiveRecord;
use componentsforyii2\extension\ConsoleController;
use componentsforyii2\extension\DbActiveRecord;

/**
 * Class BaseController
 * @package componentsforyii2\swoole\console
 */
class BaseController extends ConsoleController
{
    const SOUCE_MYSQL = "mysql";
    const SOUCE_REDIS = "redis";
    protected $dataSouce = self::SOUCE_MYSQL;
    protected $pages = [
        "page"=>1,
        "pageSize"=>20
    ];
    /**
     * 子类重写,并填写对应的模型类
     * @var array
     */
    protected $modelClassMap = [
        self::SOUCE_MYSQL=>'componentsforyii2\extension\DbActiveRecord',
        self::SOUCE_REDIS=>'componentsforyii2\extension\RedisActiveRecord',
    ];
    /**
     * @var DbActiveRecord
     */
    protected $model;
    public function runAction($id, $params = [])
    {
        if (isset($params[0]["dataSouce"])) {
            $this->dataSouce = $params[0]["dataSouce"];
            unset($params[0]["dataSouce"]);
        }
        if ((!$this->model || !$this->model instanceof BaseActiveRecord) && isset($this->modelClassMap[$this->dataSouce])) {
            $this->model = \Yii::createObject([
                "class"=>$this->modelClassMap[$this->dataSouce]
            ]);
        }
        return parent::runAction($id, $params); // TODO: Change the autogenerated stub
    }

    public function warning($message = "")
    {
        \Yii::warning(strtr($message,["{model}"=>$this->model->formName()]).":".Json::encode($this->model->getErrors()));
    }
}