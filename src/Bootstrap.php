<?php declare(strict_types=1);

namespace zymeli\ActiveRecordScope;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * 对ActiveRecord的where查询增加scope功能
 */
class Bootstrap implements BootstrapInterface
{
    /** @var string Implements of ScopeEnumInterface */
    public string $scopeEnumClass = '';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $enum = is_subclass_of($this->scopeEnumClass, ScopeEnumInterface::class) ? $this->scopeEnumClass : '';
        // 准备给每个AR都附加Scope功能，附加查询初始化时的事件处理
        $enum and Event::on(ActiveQuery::class, ActiveQuery::EVENT_INIT, function (Event $event) use ($enum) {
            /** @var ActiveQuery $query */
            $query = $event->sender;
            assert($query instanceof ActiveQuery);
            if (is_subclass_of($query->modelClass, ActiveRecord::class)) {
                $scopes = $query->modelClass::scopes();
                if ($scopes and $actives = ScopeManager::getActiveWorker()?->getActiveScopes()) {
                    Yii::debug(sprintf('Use scopes defined by %s', $enum), 'activeRecordScope');
                    $query->scopes = array_intersect_key($scopes, $actives);
                }
            }
        });
    }
}
