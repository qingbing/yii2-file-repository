<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiFileRepository\controllers\backend;


use Exception;
use YiiFileRepository\interfaces\backend\ICategoryService;
use YiiFileRepository\models\FileCategory;
use YiiFileRepository\services\backend\CategoryService;
use YiiHelper\abstracts\RestController;

/**
 * 控制器: 文件分类管理
 *
 * Class CategoryController
 * @package YiiFileRepository\controllers\backend
 *
 * @property-read ICategoryService $service
 */
class CategoryController extends RestController
{
    public $serviceInterface = ICategoryService::class;
    public $serviceClass     = CategoryService::class;

    /**
     * 文件类型 map
     *
     * @return array
     * @throws Exception
     */
    public function actionTypeMap()
    {
        // 业务处理
        $res = $this->service->typeMap();
        // 渲染结果
        return $this->success($res, '文件类型map');
    }

    /**
     * 获取文件分类选项
     *
     * @return array
     * @throws Exception
     */
    public function actionOptions()
    {
        // 业务处理
        $res = $this->service->options();
        // 渲染结果
        return $this->success($res, '文件分类选项');
    }

    /**
     * 文件分类列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'string', 'label' => '分类标识'],
            ['name', 'string', 'label' => '分类名称'],
            ['type', 'in', 'range' => array_keys(FileCategory::types()), 'label' => '类型'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
        ], null, true);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '文件分类列表');
    }

    /**
     * 添加文件分类
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'name', 'type', 'sort_order', 'is_enable'], 'required'],
            ['key', 'unique', 'label' => '分类标识', 'targetClass' => FileCategory::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '分类名称', 'targetClass' => FileCategory::class, 'targetAttribute' => 'name'],
            ['type', 'in', 'range' => array_keys(FileCategory::types()), 'label' => '类型'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['description', 'string', 'label' => '区块描述'],
            ['sort_order', 'integer', 'label' => '排序'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加文件分类成功');
    }

    /**
     * 编辑文件分类
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'name', 'sort_order',], 'required'],
            ['key', 'exist', 'label' => '分类标识', 'targetClass' => FileCategory::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '分类名称', 'targetClass' => FileCategory::class, 'targetAttribute' => 'name', 'filter' => ['!=', 'key', $key]],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['description', 'string', 'label' => '区块描述'],
            ['sort_order', 'integer', 'label' => '排序'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑文件分类成功');
    }

    /**
     * 删除文件分类
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '分类标识', 'targetClass' => FileCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除文件分类成功');
    }

    /**
     * 查看文件分类详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '分类标识', 'targetClass' => FileCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看文件分类详情');
    }
}