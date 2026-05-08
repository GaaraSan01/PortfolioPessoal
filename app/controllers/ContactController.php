<?php

class ContactController extends Controller
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');
    }

    public function index()
    {
        $data = [
            'title' => 'Contato',
            'metaDescription' => 'Entre em contato com Vinicius Henrique para novos projetos ou parcerias.'
        ];

        $this->view('contact', $data);
    }

    public function send()
    {
        
        if (!$this->isPost()) {
            $this->json([
                'success' => false,
                'message' => 'Método não permitido'
            ], 405);
        }

        try {
        
            if (!empty($_POST['website'])) {
                $this->json([
                    'success' => false,
                    'message' => 'Requisição inválida'
                ], 400);
            }

            
            if (!$this->checkRateLimit()) {
                $this->json([
                    'success' => false,
                    'message' => 'Aguarde alguns segundos antes de enviar novamente'
                ], 429);
            }

            $data = [
                'nome' => $this->sanitize($_POST['nome'] ?? ''),
                'number' => $this->sanitize($_POST['number'] ?? ''),
                'email' => $this->sanitize($_POST['email'] ?? ''),
                'mensagem' => $this->sanitize($_POST['mensagem'] ?? '')
            ];

            $validation = $this->contactModel->validate($data);

            if (!$validation['valid']) {
                $this->json([
                    'success' => false,
                    'message' => implode('. ', $validation['errors']),
                    'errors' => $validation['errors']
                ], 400);
            }

            $emailSent = $this->contactModel->sendEmail($data);

            if ($emailSent) {
                $this->registerSubmission();

                $this->contactModel->logSubmission($data, true);

                $this->json([
                    'success' => true,
                    'message' => 'Mensagem enviada com sucesso! Retornaremos em breve.'
                ]);
            } else {
                throw new Exception('Falha ao enviar o email');
            }

        } catch (Exception $e) {
            
            if (ENVIRONMENT === 'development') {
                error_log('Contact Form Error: ' . $e->getMessage());
            }

            $this->json([
                'success' => false,
                'message' => ENVIRONMENT === 'development' 
                    ? $e->getMessage() 
                    : 'Erro ao processar sua solicitação. Tente novamente mais tarde.'
            ], 500);
        }
    }

    private function checkRateLimit()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $sessionKey = 'last_submit_' . md5($ip);

        if (isset($_SESSION[$sessionKey])) {
            $timeSinceLastSubmit = time() - $_SESSION[$sessionKey];
            return $timeSinceLastSubmit >= RATE_LIMIT_TIME;
        }

        return true;
    }

    private function registerSubmission()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $sessionKey = 'last_submit_' . md5($ip);
        $_SESSION[$sessionKey] = time();
    }
}
