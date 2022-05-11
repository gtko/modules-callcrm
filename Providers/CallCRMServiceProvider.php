<?php

namespace Modules\CallCRM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CallCRM\Contracts\Repositories\AppelRepositoryContract;
use Modules\CallCRM\Repositories\AppelRepository;

class CallCRMServiceProvider extends ServiceProvider
{

    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'CallCRM';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'callcrm';

    /**
     * Register services.
     */
    public function register() :void
    {

        $this->app->bind(AppelRepositoryContract::class, AppelRepository::class);

    }

    public function boot() :void
    {
        $this->app->register(AuthServiceProvider::class);
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
