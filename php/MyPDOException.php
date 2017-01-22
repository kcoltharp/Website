<?php

/**
 * Description of MyPDOException
 *
 * @author Kenny Coltharp
 */
class MyPDOException extends PDOException{

	protected $message;
	protected $code;
	protected $file;
	protected $line;

	final public function getMessage(){
		parent::getMessage();
	}

	final public function getPrevious(){
		parent::getPrevious();
	}

	final public function getCode(){
		parent::getCode();
	}

	final public function getFile(){
		parent::getFile();
	}

	final public function getLine(){
		parent::getLine();
	}

	final public function getTrace(){
		parent::getTrace();
	}

	final public function getTraceAsString(){
		parent::getTraceAsString();
	}

	public function __toString(){
		parent::__toString();
	}

}