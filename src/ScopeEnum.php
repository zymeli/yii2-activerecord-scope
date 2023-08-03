<?php declare(strict_types=1);

namespace zymeli\ActiveRecordScope;

use Yii;
use yii\base\Application;

/**
 * 范围枚举，或类似能标识唯一name值的类模型
 */
enum ScopeEnum implements ScopeEnumInterface
{
    /**
     * 范围定义：Yii2标准条件
     */
    case ScopeByYii2Condition;

    /**
     * 范围定义：数据未删除状态
     */
    case ScopeByNotDeleted;

    /**
     * 返回枚举实例解析后的范围值
     * @param Application $app
     * @param array $params
     * @return string|array|int|float|bool|null
     */
    public function parse(Application $app, array $params = []): string|array|int|float|bool|null
    {
        return match ($this) {
            self::ScopeByNotDeleted => $this->ScopeByNotDeleted($params),
            default => $params,
        };
    }

    /**
     * 范围值：数据未删除状态
     */
    protected function ScopeByNotDeleted(array $params): array
    {
        return $params;
    }
}
