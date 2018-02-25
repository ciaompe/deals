<?php 

namespace Lucids\Helpers\Mailer;

class Message
{
	protected $_mailer;
	
	public function __construct($mailer)
	{
		$this->_mailer = $mailer;
	}

	public function to($address) {
		$this->_mailer->addAddress($address);
	}

	public function subject($subject) {
		$this->_mailer->Subject = $subject;
	}

	public function body($body) {
		$this->_mailer->Body = $body;
	}
}