<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiFileRepository\services\backend;


use YiiFileRepository\interfaces\backend\IFileService;
use YiiFileRepository\models\FileRepository;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Util;

/**
 * 服务: 文件仓库(文件池)管理
 *
 * Class FileService
 * @package YiiFileRepository\services\backend
 */
class FileService extends Service implements IFileService
{
    /**
     * 文件列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = FileRepository::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['key', 'unique_key']);
        // like 查询
        $this->likeWhere($query, $params, ['label']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加文件
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new FileRepository();
        $model->setFilterAttributes($params);
        if (empty($model->unique_key)) {
            // 界面未传递标志key时，自动创建
            $model->unique_key = Util::uniqid();
        }
        return $model->saveOrException();
    }

    /**
     * 编辑文件
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id'], $params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除文件
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
     * 查看文件详情
     *
     * @param array $params
     * @return mixed|FileRepository
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
     * @return FileRepository
     * @throws BusinessException
     */
    protected function getModel(array $params): FileRepository
    {
        $model = FileRepository::findOne([
            'id' => $params['id'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("文件不存在");
        }
        return $model;
    }
}