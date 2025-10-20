<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use ReflectionClass;
use yii\helpers\FileHelper;

/**
 * Вывод маршрутов приложения
 */
class RoutersController extends Controller
{
    public $description = 'Выводит все маршруты приложения с контроллеров и URL rules';
    public string $controllerNamespace = 'app\\controllers';

    /**
     * Выводит все маршруты приложения с контроллеров и URL rules
     *
     * Использование:
     *  php yii debug-router        - выводит все маршруты контроллеров и URL rules
     */
    public function actionDebug(): int
    {
        $this->stdout("Scanning controllers folder for routes...\n\n", Console::FG_GREEN);

        $controllerRoutes = $this->getControllerRoutes();
        $ruleRoutes = $this->getUrlManagerRoutes();

        $uniqueRules = array_diff($ruleRoutes, $controllerRoutes);

        if ($controllerRoutes) {
            $this->stdout("Controller Routes:\n", Console::FG_GREEN | Console::BOLD);
            $this->drawTable($controllerRoutes, Console::FG_YELLOW, Console::FG_YELLOW);
        }

        if ($uniqueRules) {
            $this->stdout("\nCustom URL rules (not in controllers):\n", Console::FG_GREEN | Console::BOLD);
            $this->drawTable($uniqueRules, Console::FG_GREEN, Console::FG_YELLOW);
        }

        $this->stdout("\nDone.\n", Console::FG_GREEN);
        return 0;
    }

    protected function drawTable(array $routes, int $keyColor, int $valueColor)
    {
        $keyWidth = max(array_map('strlen', array_keys($routes)));
        $valueWidth = max(array_map('strlen', array_values($routes)));

        $totalWidth = $keyWidth + $valueWidth + 7;

        // Верхняя рамка
        $this->stdout(str_repeat('═', $totalWidth) . PHP_EOL, Console::FG_GREY);

        // Заголовки
        $this->stdout('║ ', Console::FG_GREY);
        $this->stdout(str_pad('Route', $keyWidth, ' '), Console::FG_GREY | Console::BOLD);
        $this->stdout(' │ ', Console::FG_GREY);
        $this->stdout(str_pad('Path', $valueWidth, ' '), Console::FG_GREY | Console::BOLD);
        $this->stdout(' ║' . PHP_EOL, Console::FG_GREY);

        // Разделитель
        $this->stdout('╠' . str_repeat('═', $keyWidth + 2) . '╪' . str_repeat('═', $valueWidth + 2) . '╣' . PHP_EOL, Console::FG_GREY);

        // Содержимое
        foreach ($routes as $key => $value) {
            // Строка с маршрутом
            $this->stdout('║ ', Console::FG_GREY);
            $this->stdout(str_pad($key, $keyWidth, '.'), $keyColor);
            $this->stdout('...', $keyColor);
            $this->stdout(str_pad($value, $valueWidth, ' '), $valueColor);
            $this->stdout(' ║' . PHP_EOL, Console::FG_GREY);

            // Отступ в рамке (пустая строка)
            $this->stdout('║ ' . str_repeat(' ', $keyWidth) . '   ' . str_repeat(' ', $valueWidth) . ' ║' . PHP_EOL, Console::FG_GREY);
        }

        // Нижняя рамка
        $this->stdout(str_repeat('═', $totalWidth) . PHP_EOL, Console::FG_GREY);
    }



    protected function getControllerRoutes(): array
    {
        $routes = [];
        $controllersPath = \Yii::getAlias('@app/controllers');
        $files = FileHelper::findFiles($controllersPath, ['only' => ['*Controller.php']]);

        foreach ($files as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);
            $fullClassName = $this->controllerNamespace . '\\' . $className;

            if (!class_exists($fullClassName)) {
                require_once $file;
            }
            if (!class_exists($fullClassName)) continue;

            $reflection = new ReflectionClass($fullClassName);
            $controllerId = strtolower(str_replace('Controller', '', $className));

            foreach ($reflection->getMethods() as $method) {
                if (
                    $method->isPublic() &&
                    $method->getDeclaringClass()->getName() === $fullClassName &&
                    strpos($method->name, 'action') === 0 &&
                    $method->name !== 'actions'
                ) {
                    $actionId = strtolower(preg_replace('/([A-Z])/', '-$1', substr($method->name, 6)));
                    $actionId = ltrim($actionId, '-');
                    $routes[$fullClassName . '::' . $method->name . '()'] = $controllerId . '/' . $actionId;
                }
            }
        }

        return $routes;
    }

    protected function getUrlManagerRoutes(): array
    {
        $routes = [];
        $urlManager = \Yii::$app->urlManager;
        foreach ($urlManager->rules as $rule) {
            $pattern = $rule->pattern ?? '';
            $route = $rule->route ?? '';
            $routes[$pattern] = $route;
        }
        return $routes;
    }
}
