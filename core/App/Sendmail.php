<?php
namespace App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Clase de envío de emails
class Sendmail
{
	private $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Monta el mensaje que se quiere enviar en un HTML corporativo
	 *
	 * @param string $mensaje Texto a enviar
	 * @return string $mensaje Email con HTML
	 */
	public function prepareMail($message)
	{
		$mensaje = '
			<div style="width:100%; height:100%; background-color:#f0f0f0; text-align:center; font-size:12px; color:#333;">
				<br /><br />
				This is an automated email. Please do not reply directly to it.
				<br /><br />
				<div style="border:1px solid #ccc; background-color:#fff; padding:26px; width:860px; color:#333; font-size:14px; line-height:22px; text-align:left; margin:0 auto;">
				<center><a href="'._DOMINIO_.$_SESSION['lang'].'/"><img width="80" src="'._DOMINIO_.'assets/img/logo/logo-dark.png" alt="logo"></a></center>
					<br />
					'.$message.'
					<br /><br />
					<div style="border-top:1px solid #ccc; padding-top:24px; font-size:12px; color:#666;">
						This email has been sent automatically from www.fllopis.com
					</div>
				</div>
				<br /><br />
			</div>
		';
		return $mensaje;
	}

	/**
	 * Agrega el mensaje a la cola de emails de la base de datos
	 *
	 * @param string $destinatario Email del destinatario del mensaje
	 * @param string $asunto Asunto del email
	 * @param string $mensaje Texto del mensaje
	 * @return bool
	 */
	public function enqueueMail($destinatario,$asunto,$mensaje)
	{
		$addEmail = array(
			'date_created' => 'SYSDATE()',
			'destinatario' => $destinatario,
			'asunto' => $asunto,
			'mensaje' => addslashes($mensaje),	
		);

		//Guardamos cache
		if( $this->app['bd']->insert('emails_cache', $addEmail) )
			return true;
		else
			return false;
	}

	/**
	 * Envia un email corporativo en el momento
	 *
	 * @param string $emailTo Email del destinatario del mensaje
	 * @param string $subject Asunto del email
	 * @param string $message Texto del mensaje
	 * @return bool
	 */
	public function send($emailTo, $subject, $message)
	{
		$message = $this->prepareMail($message);

		require_once _PATH_.'core/Helpers/PHPMailer/PHPMailer.php';
		require_once _PATH_.'core/Helpers/PHPMailer/SMTP.php';
		require_once _PATH_.'core/Helpers/PHPMailer/Exception.php';

		$mail = new PHPMailer();

		$mail->SMTPSecure = 'tls';
		$mail->IsSMTP();
		$mail->Host = _SMTP_SERVER_;
		$mail->SMTPAuth = true;
		$mail->Port = _SMTP_PORT_;
		$mail->SetFrom(_SMTP_USER_, _TITLE_);
		$mail->Username = _SMTP_USER_;
		$mail->Password = _SMTP_PASSWORD_; 							
		$mail->SetLanguage("es");
		$mail->CharSet = _SMTP_CHARSET_ISO_;
		$mail->WordWrap = 50;					
		$mail->IsHTML(true);
		$mail->AltBody = utf8_decode(strip_tags($message));
		$mail->AddAddress($emailTo, _TITLE_);
		$mail->Subject = utf8_decode($subject);
		$mail->Body = utf8_decode($message);
		// $mail->SMTPDebug = 2;

		if( $mail->Send() )
			return true;
		return false;	
	}

	/**
	 * @todo
	 */
	public function sendCachedMail($id)
	{
		$emailData = $this->app['bd']->fetchRow('SELECT * FROM emails_cache WHERE id_email = "'.(int)$id.'"', 'array');

		if ( $emailData )
		{
			//Configuramos valores
			$destinatario = $emailData['destinatario'];
			$asunto = $emailData['asunto'];
			$mensaje = $emailData['mensaje'];
			
			if( !$this->send($destinatario, $asunto, $mensaje) )
			{
				echo 'Error al enviar email a '.$destinatario.' con asunto: "'.$asunto.'"<br/>';
				$this->app['bd']->query('UPDATE emails_cache SET error = 1 WHERE id_email = "'.$id.'"');
				return false;
			}
			else
			{
				echo 'Email enviado a '.$destinatario.' con asunto: "'.$asunto.'"<br/>';
				$this->app['bd']->query('UPDATE emails_cache SET enviado = 1, date_sent = "'.$this->app['tools']->datetime().'" WHERE id_email = "'.$id.'"');
				return true;	
			}
		}
		else
		{
			echo 'ID de email incorrecto<br/>';
			return false;
		}
	}

	public function sendTest()
	{
		require_once _PATH_.'core/Helpers/PHPMailer/PHPMailer.php';
		require_once _PATH_.'core/Helpers/PHPMailer/SMTP.php';
		require_once _PATH_.'core/Helpers/PHPMailer/Exception.php';
		
		//Configuramos valores
		$destinatario = _RECEPTOR_;
		$asunto = 'Asunto de prueba: á é í ó ú ñ.';
		$mensaje = 'Mensaje de prueba: á é í ó ú ñ.';

		$mail = new PHPMailer();

		$mail->SMTPSecure = 'tls';
		$mail->IsSMTP();
		$mail->Host = _SMTP_SERVER_;
		$mail->SMTPAuth = true;
		$mail->Port = _SMTP_PORT_;
		$mail->SetFrom(_SMTP_USER_, _TITULO_);
		$mail->Username = _SMTP_USER_;
		$mail->Password = _SMTP_PASSWORD_; 							
		$mail->SetLanguage("es");
		$mail->CharSet = _SMTP_CHARSET_ISO_;
		$mail->WordWrap = 50;
		$mail->IsHTML(true);
		$mail->AltBody = utf8_decode(strip_tags($mensaje));
		$mail->AddAddress($destinatario, _TITULO_);
		$mail->Subject = utf8_decode($asunto);
		$mail->Body = utf8_decode($mensaje);
		//$mail->SMTPDebug = 2;

		if( !$mail->Send() )
			echo 'Error al enviar email a '.$destinatario.' con asunto: "'.$asunto.'"';
		else 
			echo 'Email enviado a '.$destinatario.' con asunto: "'.$asunto.'"';
	}
}
?>
