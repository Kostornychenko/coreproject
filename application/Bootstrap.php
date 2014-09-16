<?php
Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers');

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initLayoutLoader() {
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Application_Plugin_LayoutLoader());
    }

    protected function _initConfig() {
        Zend_Registry::set('config', $this->getOptions());
    }

    protected function _initAcl() {
        $acl = new Zend_Acl();

        /*Default Resources*/
        $acl->addResource('default-index');
        $acl->addResource('default-auth');
        $acl->addResource('default-error');

        /*Admin Resources*/
        $acl->addResource('admin-index');

        /*Roles*/
        $acl->addRole('guest');
        $acl->addRole('user', 'guest');
        $acl->addRole('admin', 'user');

        /*Guest Access*/
        $acl->allow('guest', 'default-index', array('index'));
        $acl->allow('guest', 'default-auth', array('index', 'register', 'login', 'logout', 'social'));
        $acl->allow('guest', 'default-error', array('error'));

        /*User Access*/


        /*Admin Access*/
        $acl->allow('guest', 'admin-index', array('index'));

        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Application_Plugin_AccessCheck($acl, Zend_Auth::getInstance()));
    }

    protected function _initRoutes() {

        $front = Zend_Controller_Front::getInstance();
        $front->setControllerDirectory(array(
            'default' => '/default/controllers',
            'admin'    => '/admin/controllers'
        ));
        $router = $front->getRouter();


    }

}

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap {

}
