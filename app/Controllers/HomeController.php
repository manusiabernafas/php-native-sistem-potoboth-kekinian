<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ItemModel;
use App\Models\TransactionModel;

class HomeController extends Controller {
    private $itemModel;
    private $transactionModel;

    public function __construct() {
        $this->itemModel = new ItemModel();
        $this->transactionModel = new TransactionModel();
    }

    /**
     * Show the public homepage.
     */
    public function index() {
        $items = $this->itemModel->getAll();
        
        // Filter active booths
        $activeBooths = array_filter($items, function($item) {
            return strtolower($item['status'] ?? '') === 'active';
        });

        // Load donation settings
        $donation = self::getDonationSettings();

        $data = [
            'title' => 'mobooth',
            'activeBooths' => $activeBooths,
            'donation' => $donation,
            'css' => 'home'
        ];

        $this->view('home/index', $data);
    }

    /**
     * Show the About Page.
     */
    public function about() {
        $donation = self::getDonationSettings();

        $data = [
            'title' => 'About - Mobooth',
            'donation' => $donation,
            'css' => 'home'
        ];

        $this->view('home/about', $data);
    }

    /**
     * Show the Contact Page.
     */
    public function contact() {
        $donation = self::getDonationSettings();

        $data = [
            'title' => 'Contact - Mobooth',
            'donation' => $donation,
            'css' => 'home'
        ];

        $this->view('home/contact', $data);
    }

    /**
     * Show the Donate Page.
     */
    public function donate() {
        $donation = self::getDonationSettings();
        $transactions = $this->transactionModel->getAll();

        $data = [
            'title' => 'Donate - Mobooth',
            'donation' => $donation,
            'transactions' => $transactions,
            'css' => 'home'
        ];

        $this->view('home/donate', $data);
    }

    /**
     * Show the Web Photobooth Playground.
     */
    public function playground() {
        $donation = self::getDonationSettings();
        
        require_once APPROOT . '/Models/TemplateModel.php';
        $templateModel = new \App\Models\TemplateModel();
        $templates = $templateModel->getAll();

        $data = [
            'title' => 'mobooth',
            'donation' => $donation,
            'templates' => $templates,
            'css' => 'home'
        ];

        $this->view('home/playground', $data);
    }

    /**
     * Handle Player Login Simulation
     */
    public function player_login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $name = $input['name'] ?? 'Guest';
            $type = $input['type'] ?? 'Guest';
            $email = $input['email'] ?? null;

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['player_name'] = $name;
            $_SESSION['player_type'] = $type;

            // Only save to database if not a Guest
            if (strtolower($type) !== 'guest') {
                require_once APPROOT . '/Models/VisitorModel.php';
                $visitorModel = new \App\Models\VisitorModel();
                $visitorModel->create($name, $email, $type);
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'name' => $name, 'type' => $type]);
            exit;
        }
    }
}
