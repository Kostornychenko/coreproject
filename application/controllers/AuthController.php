<?php

class AuthController extends Zend_Controller_Action
{

    public function init() {

    }

    public function indexAction() {
        $this->_helper->redirector('login');
    }

    public function registerAction() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'index', 'default');
        }

        $form = new Application_Form_Register();
        $this->view->form = $form;

        $validator = new Zend_Validate_Db_NoRecordExists(
            array(
                'table' => 'users',
                'field' => 'email'
            )
        );

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if ($form->isValid($formData)) {
                $email = $this->getRequest()->getPost('email');
                if ($validator->isValid($email)) {
                    $username = $this->getRequest()->getPost('login');
                    $password = $this->getRequest()->getPost('pass');
                    $date = time();

                    $user = new Application_Model_DbTable_User();
                    $result = $user->addUser($username, md5($password), $email, $date);
                    $message = "Вы успешно зарегистрировались на сайте Serializm.com.\r\nЛогин: ".$username."\r\nПароль: ".$password."\r\nС уважением, Администрация Serializm.com";
                    $transport = new Zend_Mail_Transport_Smtp();
                    Zend_Mail::setDefaultTransport($transport);

                    $mail = new Zend_Mail('utf-8');
                    $mail->setReplyTo('admin@serializm.com', 'Администратор');
                    $mail->addHeader('MIME-Version', '1.0');
                    $mail->addHeader('Content-Transfer-Encoding', '8bit');
                    $mail->addHeader('X-Mailer:', 'PHP/'.phpversion());
                    $mail->setBodyText($message);
                    $mail->setFrom('admin@serializm.com', 'Администратор');
                    $mail->addTo($email);
                    $mail->setSubject('Успешная регистрация на serializm.com');
                    $mail->send();

                    if($result) {
                        $this->_helper->redirector('index' ,'index', 'default');
                    }
                }  else {
                    $this->view->errMessage = $validator->getMessages();
                }
            }
        }
    }

    public function loginAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'index', 'default');
        }

        $form = new Application_Form_Login();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('users')
                    ->setIdentityColumn('login')
                    ->setCredentialColumn('pass');
                $username = $this->getRequest()->getPost('login');
                $password = $this->getRequest()->getPost('pass');
                $authAdapter->setIdentity($username)
                    ->setCredential(md5($password));
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowObject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);
                    $user = Zend_Auth::getInstance()->getIdentity();
                    if ($user->role == 'admin') {
                        $this->_helper->redirector('index', 'index', 'admin');
                    } else {
                        $this->_helper->redirector('index', 'index', 'default');
                    }
                } else {
                    $this->view->errMessage = 'Вы ввели неверное имя пользователя или пароль!';
                }
            }
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index', 'index', 'default');
    }

    public function socialAction() {
        header('Content-type: text/html; charset=UTF-8');

        $token = $_POST['access_token'];
        $host = $_SERVER['SERVER_NAME'];

        $url = 'http://login4play.com/token.php?token=' . $token . '&host=' . $host;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        $username = $data['given_name'];
        $password = $data['uid'];
        $email = $data['email'];
        $network = $data['network'];
        $date = $date = time();
        $validator = new Zend_Validate_Db_NoRecordExists(
            array(
                'table' => 'users',
                'field' => 'pass'
            )
        );
        if ($validator->isValid(md5($password))) {
            $user = new Application_Model_DbTable_User();
            $user->addUser($username, md5($password), $email, $date, $network);
        }
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('users')
            ->setIdentityColumn('login')
            ->setCredentialColumn('pass');
        $authAdapter->setIdentity($username)
            ->setCredential(md5($password));
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $identity = $authAdapter->getResultRowObject();
            $authStorage = $auth->getStorage();
            $authStorage->write($identity);
            $this->_helper->redirector('index', 'index', 'default');
        } else {
            $this->view->errMessage = 'Ви ввели не правильний логін або пароль!';
        }

    }

}







