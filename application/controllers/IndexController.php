<?php

class IndexController extends Zend_Controller_Action {

    public function init() {

    }

    public function indexAction() {

    }

    public function photosAction() {
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass('Zend_Http_Client');

        $CLIENT_ID = 'fa2641332bca4ce6bd8f71bd4345cc0f';
        $CLIENT_SECRET = 'c44e29e7449444e3bc1f58ff499f5e8e';
        $user = '332756956';
        $tag = 'coreproject';

        try {
            $client = new Zend_Http_Client('https://api.instagram.com/v1/users/'.$user.'/media/recent');
            $client->setParameterGet('client_id', $CLIENT_ID);
            $response = $client->request();
            $result = json_decode($response->getBody());
            $data = $result->data;
            if (count($data) > 0) {
                $this->view->user = $data;
            }
        } catch (Exception $e) {
            echo 'ERROR: ' . $e->getMessage() . print_r($client);
            exit;
        }

        try {
            $client = new Zend_Http_Client('https://api.instagram.com/v1/tags/'.$tag.'/media/recent');
            $client->setParameterGet('client_id', $CLIENT_ID);
            $response = $client->request();
            $result = json_decode($response->getBody());
            $data = $result->data;
            if (count($data) > 0) {
                $this->view->tag = $data;
            }

        } catch (Exception $e) {
            echo 'ERROR: ' . $e->getMessage() . print_r($client);
            exit;
        }
    }
}

