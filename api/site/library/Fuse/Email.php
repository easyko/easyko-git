<?php
// No direct access
//defined('PATH_BASE') or die;

include_once('Email'.DS.'phpmailer'.DS.'class.phpmailer.php');
include_once('Email'.DS.'Mailhelper.php');

/**
 * Email Class.  Provides a common interface to send email from the Imag Framework
 *
 * @package		Imag.Framework
 * @subpackage	mail
 * @author   modify by gary wang (wangbaogang123@hotmail.com)
 *
 */
class Fuse_Email extends PHPMailer
{
	/**
	 * Constructor
	 */
	public function __construct($options=null)
	{
		if(empty($options)){
			$options = new Config_Email();
		}
		$this->setConfig($options);
	}

	/**
	 * Returns the global email object, only creating it
	 * if it doesn't already exist.
	 *
	 * NOTE: If you need an instance to use that does not have the global configuration
	 * values, use an id string that is not 'Imag'.
	 *
	 * @param	string	$id		The id string for the Email instance [optional]
	 *
	 * @return	object	The global Email object
	 */
	public static function getInstance($id = 'imag')
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty($instances[$id])) {
			$instances[$id] = new Fuse_Email();
		}

		return $instances[$id];
	}

	/**
	 * Send the mail
	 *
	 * @return	mixed	True if successful, a Exception object otherwise
	 */
	public function Send()
	{
		if (($this->Mailer == 'mail') && ! function_exists('mail')) {
			return new Exception('LIB_MAIL_FUNCTION_DISABLED');
		}

		@$result = parent::Send();

		if ($result == false) {
			// TODO: Set an appropriate error number
			$result = new Exception($this->ErrorInfo);
		}

		return $result;
	}

	/**
	 * Set the email sender
	 *
	 * @param	array	email address and Name of sender
	 *		<pre>
	 *			array([0] => email Address [1] => Name)
	 *		</pre>
	 *
	 * @return	Email	Returns this object for chaining.
	 */
	public function setSender($from)
	{
		if (is_array($from)) {
			// If $from is an array we assume it has an address and a name
			$this->From	= MailHelper::cleanLine($from[0]);
			$this->FromName = MailHelper::cleanLine($from[1]);

		}
		elseif (is_string($from)) {
			// If it is a string we assume it is just the address
			$this->From = MailHelper::cleanLine($from);

		}
		else {
			// If it is neither, we throw a warning
			throw Exception("LIB_MAIL_INVALID_EMAIL_SENDER:".$from);
		}

		return $this;
	}

	/**
	 * Set the email subject
	 *
	 * @param	string	$subject	Subject of the email
	 *
	 * @return	Email	Returns this object for chaining.
	 *
	 */
	public function setSubject($subject)
	{
		$this->Subject = MailHelper::cleanLine($subject);

		return $this;
	}

	/**
	 * Set the email body
	 *
	 * @param	string	$content	Body of the email
	 *
	 * @return	Email	Returns this object for chaining.
	 */
	public function setBody($content)
	{
		/*
		 * Filter the Body
		 * TODO: Check for XSS
		 */
		$this->Body = MailHelper::cleanText($content);

		return $this;
	}

	/**
	 * Add recipients to the email
	 *
	 * @param	mixed	$recipient	Either a string or array of strings [email address(es)]
	 *
	 * @return	Email	Returns this object for chaining.
	 */
	public function addRecipient($recipient)
	{
		// If the recipient is an aray, add each recipient... otherwise just add the one
		if (is_array($recipient)) {
			foreach ($recipient as $to)
			{
				$to = MailHelper::cleanLine($to);
				$this->AddAddress($to);
			}
		}
		else {
			$recipient = MailHelper::cleanLine($recipient);
			$this->AddAddress($recipient);
		}

		return $this;
	}

	/**
	 * Add carbon copy recipients to the email
	 *
	 * @param	mixed	$cc		Either a string or array of strings [email address(es)]
	 *
	 * @return	Email	Returns this object for chaining.
	 */
	public function addCC($cc)
	{
		// If the carbon copy recipient is an aray, add each recipient... otherwise just add the one
		if (isset ($cc)) {
			if (is_array($cc)) {
				foreach ($cc as $to)
				{
					$to = MailHelper::cleanLine($to);
					parent::AddCC($to);
				}
			}
			else {
				$cc = MailHelper::cleanLine($cc);
				parent::AddCC($cc);
			}
		}

		return $this;
	}

	/**
	 * Add blind carbon copy recipients to the email
	 *
	 * @param	mixed	$bcc	Either a string or array of strings [email address(es)]
	 *
	 * @return	Email	Returns this object for chaining.
	 */
	public function addBCC($bcc)
	{
		// If the blind carbon copy recipient is an aray, add each recipient... otherwise just add the one
		if (isset($bcc)) {
			if (is_array($bcc)) {
				foreach ($bcc as $to)
				{
					$to = MailHelper::cleanLine($to);
					parent::AddBCC($to);
				}
			}
			else {
				$bcc = MailHelper::cleanLine($bcc);
				parent::AddBCC($bcc);
			}
		}

		return $this;
	}

	/**
	 * Add file attachments to the email
	 *
	 * @param	mixed	$attachment	Either a string or array of strings [filenames]
	 *
	 * @return	Email	Returns this object for chaining.
	 *
	 */
	public function addAttachment($attachment)
	{
		// If the file attachments is an aray, add each file... otherwise just add the one
		if (isset($attachment)) {
			if (is_array($attachment)) {
				foreach ($attachment as $file)
				{
					parent::AddAttachment($file);
				}
			}
			else {
				parent::AddAttachment($attachment);
			}
		}

		return $this;
	}

	/**
	 * Add Reply to email address(es) to the email
	 *
	 * @param	array	$replyto	Either an array or multi-array of form
	 *		<pre>
	 *			array([0] => email Address [1] => Name)
	 *		</pre>
	 *
	 * @return	Email	Returns this object for chaining.
	 */
	public function addReplyTo($replyto)
	{
		// Take care of reply email addresses
		if (is_array($replyto[0])) {
			foreach ($replyto as $to)
			{
				$to0 = MailHelper::cleanLine($to[0]);
				$to1 = MailHelper::cleanLine($to[1]);
				parent::AddReplyTo($to0, $to1);
			}
		}
		else {
			$replyto0 = MailHelper::cleanLine($replyto[0]);
			$replyto1 = MailHelper::cleanLine($replyto[1]);
			parent::AddReplyTo($replyto0, $replyto1);
		}

		return $this;
	}

	/**
	 * Use sendmail for sending the email
	 *
	 * @param	string	$sendmail	Path to sendmail [optional]
	 * @return	boolean	True on success
	 *
	 */
	public function useSendmail($sendmail = null)
	{
		$this->Sendmail = $sendmail;

		if (!empty ($this->Sendmail)) {
			$this->IsSendmail();

			return true;
		}
		else {
			$this->IsMail();

			return false;
		}
	}

	/**
	 * Use SMTP for sending the email
	 *
	 * @param	string	$auth	SMTP Authentication [optional]
	 * @param	string	$host	SMTP Host [optional]
	 * @param	string	$user	SMTP Username [optional]
	 * @param	string	$pass	SMTP Password [optional]
	 * @param			$secure
	 * @param	int		$port
	 *
	 * @return	boolean	True on success
	 *
	 */
	public function useSMTP($auth = null, $host = null, $user = null, $pass = null, $secure = null, $port = 25)
	{
		$this->SMTPAuth = $auth;
		$this->Host		= $host;
		$this->Username = $user;
		$this->Password = $pass;
		$this->Port		= $port;

		if ($secure == 'ssl' || $secure == 'tls') {
			$this->SMTPSecure = $secure;
		}

		if (($this->SMTPAuth !== null && $this->Host !== null && $this->Username !== null && $this->Password !== null)
			|| ($this->SMTPAuth === null && $this->Host !== null)) {
			$this->IsSMTP();

			return true;
		}
		else {
			$this->IsMail();

			return false;
		}
	}

	/**
	 * Function to send an email
	 *
	 * @param	string	$from			From email address
	 * @param	string	$fromName		From name
	 * @param	mixed	$recipient		Recipient email address(es)
	 * @param	string	$subject		email subject
	 * @param	string	$body			Message body
	 * @param	boolean	$mode			false = plain text, true = HTML
	 * @param	mixed	$cc				CC email address(es)
	 * @param	mixed	$bcc			BCC email address(es)
	 * @param	mixed	$attachment		Attachment file name(s)
	 * @param	mixed	$replyTo		Reply to email address(es)
	 * @param	mixed	$replyToName	Reply to name(s)
	 *
	 * @return	boolean	True on success
	 *
	 */
	public function sendMail($from, $fromName, $recipient, $subject, $body, $mode=0,
		$cc=null, $bcc=null, $attachment=null, $replyTo=null, $replyToName=null)
	{
		$this->setSender(array($from, $fromName));
		$this->setSubject($subject);
		$this->setBody($body);

		// Are we sending the email as HTML?
		if ($mode) {
			$this->IsHTML(true);
		}

		$this->addRecipient($recipient);
		$this->addCC($cc);
		$this->addBCC($bcc);
		$this->addAttachment($attachment);

		// Take care of reply email addresses
		if (is_array($replyTo)) {
			$numReplyTo = count($replyTo);

			for ($i = 0; $i < $numReplyTo; $i++)
			{
				$this->addReplyTo(array($replyTo[$i], $replyToName[$i]));
			}
		}
		else if (isset($replyTo)) {
			$this->addReplyTo(array($replyTo, $replyToName));
		}

		return  $this->Send();
	}

	/**
	 * Set config
	 *
	 * @param	stdClass	$config			email config
	 *
	 * $object = new stdClass();
	 * $object->host = ;
	 * $object->from = ;
	 * $object->fromname = ;
	 * $object->passwd = ;
	 *
	*/
	public function setConfig($config){


		$this->IsSMTP();                         // send via SMTP
		
		/* */
		 $this->Host     = $config->host;        // SMTP servers
		 $this->SMTPAuth = true;                // turn on SMTP authentication
		 $this->Username = $config->from;       // SMTP username  注意：普通邮件认证不需要加 @域名
		 $this->Password = $config->passwd;     // SMTP password
		 $this->From     = $config->fromemail;  // 发件人邮箱
		 $this->AddReplyTo($config->from);      //答复地址必须和发件人一致

		 $this->FromName =  $config->fromname;  // 发件人

		 $this->CharSet  = $config->charset;     // 这里指定字符集！
		 $this->Encoding = "base64";
		 $this->IsHTML(true);  // send as HTML

		 $this->AltBody = $config->altbody;

	}

	/**
	 * send email
	 * @param	string	$to			to email
	 * @param	string	$subject    email subject
	 * @param	string	$body       email body
	 * @return	boolean	True on success
	 */
	public function sendEmail($to,$subject,$body){


		$this->setSubject($subject);
		$this->setBody($body);

		// 邮件主题
		 $this->Subject = $subject;
		 // 邮件内容
		 $this->Body = $body;
		 $this->AddAddress($to,"");
		 return $this->Send();
	}

}