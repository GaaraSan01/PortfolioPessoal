<?php

class ContactModel extends Model
{

    public function validate($data)
    {
        $errors = [];

        
        if (empty($data['nome'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (!$this->validateName($data['nome'])) {
            $errors[] = 'Nome inválido';
        }

        if (empty($data['number'])) {
            $errors[] = 'Numero é obrigatório';
        } elseif (!$this->validateNumber($data['number'])) {
            $errors[] = 'Numero inválido';
        }

        if (empty($data['email'])) {
            $errors[] = 'E-mail é obrigatório';
        } elseif (!$this->validateEmail($data['email'])) {
            $errors[] = 'E-mail inválido';
        }

        if (empty($data['mensagem'])) {
            $errors[] = 'Mensagem é obrigatória';
        } elseif (!$this->validateMinLength($data['mensagem'], 10)) {
            $errors[] = 'Mensagem muito curta (mínimo 10 caracteres)';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    public function sendEmail($data)
    {
        $to = EMAIL_TO;
        $subject = '[' . APP_NAME . '] Nova mensagem de contato';
        
        $htmlBody = $this->getEmailTemplate($data);
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . EMAIL_FROM_NAME . ' <' . EMAIL_FROM . '>',
            'Reply-To: ' . $data['nome'] . ' <' . $data['email'] . '>',
            'X-Mailer: PHP/' . phpversion(),
            'X-Priority: 1',
            'Importance: High'
        ];

        return mail($to, $subject, $htmlBody, implode("\r\n", $headers));
    }

    private function getEmailTemplate($data)
    {
        ob_start();
        require VIEWS_PATH . '/emails/contact.php';
        return ob_get_clean();
    }

    public function logSubmission($data, $success)
    {
        $logFile = ROOT . '/contact-log.txt';
        $logEntry = sprintf(
            "[%s] IP: %s | Email: %s | Nome: %s | Status: %s\n",
            date('Y-m-d H:i:s'),
            $_SERVER['REMOTE_ADDR'],
            $data['email'],
            $data['nome'],
            $success ? 'SUCCESS' : 'FAILED'
        );

        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
