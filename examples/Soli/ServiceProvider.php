<?php

namespace Soli;

abstract class ServiceProvider extends Component
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    abstract public function register();

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * 绑定服务到容器
     */
    public function bind()
    {
        $realProvide = current($this->provides());
        if (empty($realProvide)) {
            return;
        }

        foreach ($this->provides() as $provide) {
            if ($provide == $realProvide) {
                $this->di->set(
                    $provide,
                    [$this, 'register'],
                    $this->defer
                );
            } else {
                $this->di->set(
                    $provide,
                    function () use ($realProvide) {
                        return $this->di->get($realProvide);
                    },
                    $this->defer
                );
            }
        }
    }
}
