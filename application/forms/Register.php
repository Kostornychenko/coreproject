<?php

class Application_Form_Register extends Zend_Form {

    public function init() {
        $this->setName('register');
        $isEmptyMessage = 'Значение является обязательным и не может быть пустым';

        $username = new Zend_Form_Element_Text('login');
        $username->setLabel('Логин:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDescription('Логин должен быть не короче 4 символов, содержать буквы, а также цифры или спецсимволы')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            );

        $password = new Zend_Form_Element_Password('pass');
        $password->setLabel('Пароль:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDescription('Пароль должен быть не короче 4 символов, содержать буквы, а также цифры или спецсимволы')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            );


        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-mail:')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDescription('Пожалуйста, вводите реальный e-mail, на него будет выслано письмо с регистрационными данными')
            ->addValidator('NotEmpty', true,
                array('messages' => array('isEmpty' => $isEmptyMessage))
            );


        $submit = new Zend_Form_Element_Submit('register');
        $submit->setLabel('Зарегистрироваться');

        $this->addElements(array($username, $password, $email, $submit));

        $this->setMethod('post');
    }


}

