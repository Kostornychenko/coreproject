<?php

class Application_Form_Login extends Zend_Form {

    public function init() {
        $this->setName('loginform');

        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';

        $username = new Zend_Form_Element_Text('login');

        $username->setLabel('Логин:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            );

        $password = new Zend_Form_Element_Password('pass');

        $password->setLabel('Пароль:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            );

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Войти');

        $this->addElements(array($username, $password, $submit));

        $this->setMethod('post');
    }


}

