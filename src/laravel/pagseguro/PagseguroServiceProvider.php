<?php

namespace laravel\pagseguro;

use \laravel\pagseguro\Credentials\Credentials,
    \laravel\pagseguro\Request\PaymentRequest,
    \laravel\pagseguro\Request\SessionPaymentRequest,
    \Illuminate\Support\ServiceProvider,
    \Config;

/**
 * Classe responsável por prover o serviço do Laravel PagSeguro ao Framework
 *
 * @category   ServiceProvider
 * @package    Laravel\PagSeguro
 *
 * @author     Michael Douglas <michaeldouglas010790@gmail.com>
 * @since      2015-01-02
 *
 * @copyright  Laravel\PagSeguro
 */
class PagseguroServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     * @var bool
     */
    protected $defer = true;

    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * Bootstrap the application events.
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . "/config/laravelpagseguro.php" => config_path('laravelpagseguro.php')]);
        
        #Registra os comandos da Laravel PagSeguro
        $this->commands('\laravel\pagseguro\Commands\PagSeguroSessionPaymentCommand');
    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->bind('pagseguro', function () {
            $this->loadCredentials();
            return new PaymentRequest($this->credentials);
        });
        
        #Registro para a classe de retorno de sessão de pagamento
        $this->app->singleton('session', function(){
        	return new SessionPaymentRequest();
        });
    }

    /**
     * Load Credentials From Config
     * @return void
     */
    public function loadCredentials()
    {
        $this->credentials = new Credentials(Config('laravelpagseguro.credentials.token'), Config('laravelpagseguro.credentials.email'));
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return array('pagseguro');
    }

}