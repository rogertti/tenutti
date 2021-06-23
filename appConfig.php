<?php
    #ini_set('display_errors', 'On');
    #ini_set('output_buffering', 4096);
    #ini_set('session.auto_start', 1);
    #ini_set('SMTP', 'smtp.embracore.com.br');
    #ini_set('smtp_port', 587);
    #error_reporting(0);
    session_start();
    date_default_timezone_set('America/Sao_Paulo');
    setlocale(LC_MONETARY, 'pt_BR');
    
    $cfg = array(
        'head_title'=>'Tenutti',
        'login_title'=>'<strong>Tenutti</strong>',
        'side_title'=>'Tenutti'
    );
