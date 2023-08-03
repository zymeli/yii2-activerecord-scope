<?php declare(strict_types=1);

namespace zymeli\ActiveRecordScope;

use Yii;

/**
 * 保存Scope的信息
 */
class ScopeWorker
{
    /** @var array 当前活动的scope功能集 */
    protected array $activeScopes = [];

    /**
     * 启用某个scope功能
     * @param ScopeEnumInterface $scopeEnum 启用scope[名称=>scopeEnumInstance]
     * @return void
     */
    public function enableScope(ScopeEnumInterface $scopeEnum): void
    {
        $name = $scopeEnum->name;
        if (!array_key_exists($name, $this->activeScopes)) {
            Yii::debug(sprintf('Enable scope: %s', $name), 'activeRecordScope');
            $this->activeScopes[$name] = $scopeEnum;
        }
    }

    /**
     * 禁用某个scope功能
     * @param ScopeEnumInterface $scopeEnum 删除scope[名称]
     * @return void
     */
    public function disableScope(ScopeEnumInterface $scopeEnum): void
    {
        $name = $scopeEnum->name;
        if (array_key_exists($name, $this->activeScopes)) {
            Yii::debug(sprintf('Disable scope: %s', $name), 'activeRecordScope');
            unset($this->activeScopes[$name]);
        }
    }

    /**
     * 返回当前活动的scope功能集
     * @return array
     */
    public function getActiveScopes(): array
    {
        return $this->activeScopes;
    }

    /**
     * 清空scope功能集
     */
    public function emptyScopes(): void
    {
        Yii::debug('Empty all scopes', 'activeRecordScope');
        $this->activeScopes = [];
    }
}
