<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiFileRepository\controllers\backend;


use Exception;
use YiiFileRepository\interfaces\backend\IFileService;
use YiiFileRepository\models\FileCategory;
use YiiFileRepository\models\FileRepository;
use YiiFileRepository\services\backend\FileService;
use YiiHelper\abstracts\RestController;

/**
 * 控制器: 文件仓库(文件池)管理
 *
 * Class FileController
 * @package YiiFileRepository\controllers\backend
 *
 * @property-read IFileService $service
 */
class FileController extends RestController
{
    public $serviceInterface = IFileService::class;
    public $serviceClass     = FileService::class;

    /**
     * 文件列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'in', 'label' => '分类', 'range' => array_keys(FileCategory::getOptions())],
            ['unique_key', 'string', 'label' => '分类标识'],
            ['label', 'string', 'label' => '文件名称'],
        ]);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '区块选项列表');
    }

    /**
     * 添加文件
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'label', 'url', 'sort_order'], 'required'],
            ['key', 'in', 'label' => '分类', 'range' => array_keys(FileCategory::getOptions())],
            ['unique_key', 'unique', 'label' => '分类标识', 'targetClass' => FileRepository::class, 'targetAttribute' => 'unique_key'],
            ['label', 'unique', 'label' => '文件名称', 'targetClass' => FileRepository::class, 'targetAttribute' => 'label', 'filter' => ['key' => $key]],
            ['url', 'string', 'label' => '文件链接'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['description', 'string', 'label' => '描述'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加文件成功');
    }

    /**
     * 编辑文件
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 数据提前获取
        $id  = $this->getParam('id');
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['id', 'key', 'label'], 'required'],
            ['key', 'string', 'label' => '分类'],
            [
                'id',
                'exist',
                'label'           => 'ID',
                'targetClass'     => FileRepository::class,
                'targetAttribute' => 'id',
                'filter'          => ['=', 'key', $key],
            ],
            [
                'label',
                'unique',
                'label'           => '文件名称',
                'targetClass'     => FileRepository::class,
                'targetAttribute' => 'label',
                'filter'          => [
                    'and',
                    ['key' => $key],
                    ['!=', 'id', $id],
                ]
            ],
            ['url', 'string', 'label' => '文件链接'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['description', 'string', 'label' => '描述'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑文件成功');
    }

    /**
     * 删除文件
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FileRepository::class, 'targetAttribute' => 'id',],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除文件成功');
    }

    /**
     * 查看文件详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FileRepository::class, 'targetAttribute' => 'id',],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看文件详情');
    }
}