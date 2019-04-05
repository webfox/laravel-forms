<?php

namespace Webfox\LaravelForms;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath('config/config.php'), 'forms');

        $this->app->singleton(FormModelStack::class, function () {
            return new FormModelStack;
        });

        $this->app->bind(AttributeManager::class, function () {
            return new AttributeManager($this->app->make(FormModelStack::class));
        });
    }

    public function boot()
    {
        $this->loadViewsFrom($this->packagePath('views'), 'forms');
        $this->publishes([$this->packagePath('views') => resource_path('views/vendor/forms')], 'views');
        $this->publishes([$this->packagePath('config/config.php') => config_path('forms.php')], 'config');

        Blade::include(config('forms.container_path'), 'formField');

        Blade::directive('form', function ($expression) {
            return '<?php echo app()->make(\Webfox\LaravelForms\Form::class, ["options" => '. $expression .'])->open(); ?>';
        });

        Blade::directive('endform', function() {
            return '<?php echo app(\Webfox\LaravelForms\Form::class)->close(); ?>';
        });

        Blade::directive('pushFormModel', function ($expression) {
            return '<?php app(\Webfox\LaravelForms\FormModelStack::class)->push(' . $expression . '); ?>';
        });

        Blade::directive('popFormModel', function () {
            return '<?php app(\Webfox\LaravelForms\FormModelStack::class)->pop(); ?>';
        });
        View::composer(config('forms.container_path'), FieldViewComposer::class);
    }

    /**
     * Loads a path relative to the package base directory.
     *
     * @param string $path
     *
     * @return string
     */
    protected function packagePath($path = '')
    {
        return sprintf('%s/../%s', __DIR__, $path);
    }
}
