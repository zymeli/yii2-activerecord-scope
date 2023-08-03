<?php declare(strict_types=1);

namespace zymeli\ActiveRecordScope;

use Yii;

/**
 * 静态记录scope.worker的嵌套信息和活动状态
 */
class ScopeManager
{
    /** @var array 每一级嵌套的worker信息 */
    private static array $nestWorkers = [0 => null];

    /** @var int 活动状态，标记AR适用哪一级 */
    private static int $activeLevel = 0;

    /**
     * 打开嵌套，附加worker信息
     * @param callable $callback 打开嵌套，开始scope.worker处理
     * @param ?ScopeWorker $newWorker 使用新worker或克隆上级嵌套的worker值
     * @return mixed 返回callback的执行结果
     */
    public static function execute(callable $callback, ScopeWorker $newWorker = null): mixed
    {
        $oldWorker = self::$nestWorkers[self::$activeLevel];
        self::$activeLevel++;
        Yii::debug(sprintf('Start use scopes at nest level %s', self::$activeLevel), 'activeRecordScope');
        $newWorker or $newWorker = ($oldWorker ? clone($oldWorker) : new ScopeWorker());
        self::$nestWorkers[self::$activeLevel] = $newWorker;
        $result = call_user_func($callback, $newWorker);
        unset(self::$nestWorkers[self::$activeLevel]);
        Yii::debug(sprintf('End use scopes at nest level %s', self::$activeLevel), 'activeRecordScope');
        self::$activeLevel--;
        return $result;
    }

    /**
     * 返回当前活动的worker信息
     * @return ?ScopeWorker
     */
    public static function getActiveWorker(): ?ScopeWorker
    {
        return self::$nestWorkers[self::$activeLevel];
    }

    /**
     * 返回当前活动的Level嵌套级
     * @return int
     */
    public static function getActiveLevel(): int
    {
        return self::$activeLevel;
    }
}
