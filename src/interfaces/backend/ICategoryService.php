<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiFileRepository\interfaces\backend;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口: 文件分类管理
 *
 * Interface ICategoryService
 * @package YiiFileRepository\interfaces\backend
 */
interface ICategoryService extends ICurdService
{
    /**
     * 文件类型 map
     *
     * @return array
     */
    public function typeMap(): array;

    /**
     * 获取文件分类选项
     *
     * @return array
     */
    public function options(): array;
}