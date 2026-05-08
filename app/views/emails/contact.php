<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0;
            padding: 0;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .header { 
            background: #FF4400; 
            color: white; 
            padding: 20px; 
            text-align: center; 
        }
        .header h2 {
            margin: 0;
        }
        .content { 
            background: #f9f9f9; 
            padding: 30px; 
            border: 1px solid #ddd; 
        }
        .field { 
            margin-bottom: 20px; 
        }
        .label { 
            font-weight: bold; 
            color: #FF4400;
            margin-bottom: 5px;
        }
        .value {
            color: #333;
            word-wrap: break-word;
        }
        .footer { 
            text-align: center; 
            padding: 20px; 
            color: #888; 
            font-size: 12px; 
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Nova Mensagem de Contato</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Nome:</div>
                <div class='value'><?= nl2br(htmlspecialchars($data['nome'], ENT_QUOTES, 'UTF-8')) ?></div>
            </div>
            <div class='field'>
                <div class='label'>E-mail:</div>
                <div class='value'><?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class='field'>
                <div class='label'>Numero:</div>
                <div class='value'><?= htmlspecialchars($data['number'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class='field'>
                <div class='label'>Mensagem:</div>
                <div class='value'><?= nl2br(htmlspecialchars($data['mensagem'], ENT_QUOTES, 'UTF-8')) ?></div>
            </div>
            <div class='field'>
                <div class='label'>Data/Hora:</div>
                <div class='value'><?= date('d/m/Y H:i:s') ?></div>
            </div>
            <div class='field'>
                <div class='label'>IP:</div>
                <div class='value'><?= htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        </div>
        <div class='footer'>
            <p>Este email foi enviado automaticamente pelo formulário de contato do site.</p>
            <p><?= APP_NAME ?></p>
        </div>
    </div>
</body>
</html>
