<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiFileRepository\services\backend;


use YiiFileRepository\interfaces\backend\ICategoryService;
use YiiFileRepository\models\FileCategory;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务: 文件分类分类管理
 *
 * Class CategoryService
 * @package YiiFileRepository\services\backend
 */
class CategoryService extends Service implements ICategoryService
{
    /**
     * 文件类型 map
     *
     *
     * @return array
     */
    public function typeMap(): array
    {
        return FileCategory::types();
    }

    /**
     * 获取文件分类选项
     *
     * @return array
     */
    public function options(): array
    {
        return FileCategory::getOptions();
    }

    /**
     * 文件分类列表
     *
     * @param array $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = FileCategory::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['type', 'is_enable']);
        // like 查询
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加文件分类
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new FileCategory();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑文件分类
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除文件分类
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        return $this->getModel($params)->delete();
    }

    /**
     * 查看文件分类详情
     *
     * @param array $params
     * @return mixed|FileCategory
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return FileCategory
     * @throws BusinessException
     */
    protected function getModel(array $params): FileCategory
    {
        $model = FileCategory::findOne([
            'key' => $params['key'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("分类不存在");
        }
        return $model;
    }
}