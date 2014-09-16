<?php

class IndexController extends Zend_Controller_Action {

    public function init() {

    }

    public function indexAction() {

    }

    public function photosAction() {
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass('Zend_Http_Client');

        // определение ключа и секретного ключа пользователя
        // доступно с консоли API Instagram
        $CLIENT_ID = 'fa2641332bca4ce6bd8f71bd4345cc0f';
        $CLIENT_SECRET = 'c44e29e7449444e3bc1f58ff499f5e8e';
        $tag = '332756956';
        $tag1 = 'coreproject';

        try {
            // initialize client
            $client = new Zend_Http_Client('https://api.instagram.com/v1/users/'.
                $tag . '/media/recent');
            $client->setParameterGet('client_id', $CLIENT_ID);

            // получение изображений с соответствующими метками
            // передача запроса и декодирование ответа
            $response = $client->request();
            $result = json_decode($response->getBody());

            // отображение фотографий
            $data = $result->data;
            if (count($data) > 0) {
                $this->view->user = $data;

            }

        } catch (Exception $e) {
            echo 'ERROR: ' . $e->getMessage() . print_r($client);
            exit;
        }

    }
}

