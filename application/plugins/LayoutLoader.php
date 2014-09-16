<?php
class Application_Plugin_LayoutLoader extends Zend_Controller_Plugin_Abstract
{

    public  function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $this->getRequest()->getModuleName();
        $layout = Zend_Layout::getMvcInstance();

        if ($module == 'admin') {
            $layout->setLayout('admin');
        }
    }

}