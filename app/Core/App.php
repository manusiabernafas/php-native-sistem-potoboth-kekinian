<?php

namespace App\Core;

class App {
    protected $controller = 'App\Controllers\HomeController'; // Default controller
    protected $method = 'index'; // Default method
    protected $params = []; // Parameters

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Determine Controller
        if (!empty($url)) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerClass = 'App\Controllers\\' . $controllerName;
            
            // Check if controller file exists in the filesystem
            if (file_exists(APPROOT . '/Controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerClass;
                unset($url[0]);
            } else {
                // If controller doesn't exist, route to ErrorController
                $this->controller = 'App\Controllers\ErrorController';
            }
        }

        // Instantiate the controller (autoloaded automatically)
        if (class_exists($this->controller)) {
            $this->controller = new $this->controller;
        } else {
            // Fallback if class not found
            $errorClass = 'App\Controllers\ErrorController';
            $this->controller = new $errorClass;
        }

        // 2. Determine Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // If method is invalid, redirect to error or standard index
                $this->method = 'index';
            }
        }

        // 3. Determine Parameters
        $this->params = $url ? array_values($url) : [];

        // 4. Call Controller Method with parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse the URL query string 'url' into an array.
     */
    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
