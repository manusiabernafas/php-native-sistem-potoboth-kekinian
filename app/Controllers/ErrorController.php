<?php

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller {
    /**
     * Render the 404 Not Found view.
     */
    public function index() {
        header("HTTP/1.0 404 Not Found");
        
        $data = [
            'title' => 'Halaman Tidak Ditemukan - 404',
            'message' => 'Halaman yang Anda cari tidak dapat ditemukan.'
        ];
        
        $this->view('error/404', $data);
    }
}
