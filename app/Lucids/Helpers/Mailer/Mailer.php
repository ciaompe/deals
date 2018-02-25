<?php

namespace Lucids\Helpers\Mailer;
use \Exception;


/*
Mailer Helper class for lucids development app
*/

class Mailer {

	protected $_view, $_mailer;

	public function __construct($view, $mailer) {

		$this->_view = $view;
		$this->_mailer = $mailer;

	}

	public function send($template, $data, $callback) {

		$message = new Message($this->_mailer);

		$this->_view->appendData($data);

		$message->body($this->_view->render($template));

		call_user_func($callback, $message);

		if(!$this->_mailer->send()) {
	        throw new Exception($this->_mailer->ErrorInfo);
	    }
	}
}