<?php
/**
 * @author Bart Visscher <bartv@thisnet.nl>
 * @author Bjoern Schiessle <schiessle@owncloud.com>
 * @author Morris Jobke <hey@morrisjobke.de>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */
namespace OC\Log;

use OC\Log as LoggerInterface;

class ErrorHandler {
	/** @var LoggerInterface */
	private static $logger;

	/**
	 * remove password in URLs
	 * @param string $msg
	 * @return string
	 */
	protected static function removePassword($msg) {
		return preg_replace('/\/\/(.*):(.*)@/', '//xxx:xxx@', $msg);
	}

	public static function register($debug=false) {
		$handler = new ErrorHandler();

		if ($debug) {
			set_error_handler(array($handler, 'onAll'), E_ALL);
		} else {
			set_error_handler(array($handler, 'onError'));
		}
		register_shutdown_function(array($handler, 'onShutdown'));
		set_exception_handler(array($handler, 'onException'));
	}

	public static function setLogger(LoggerInterface $logger) {
		self::$logger = $logger;
	}

	//Fatal errors handler
	public static function onShutdown() {
		$error = error_get_last();
		if($error && self::$logger) {
			//ob_end_clean();
			$msg = $error['message'] . ' at ' . $error['file'] . '#' . $error['line'];
			self::$logger->critical(self::removePassword($msg), array('app' => 'PHP'));
		}
	}

	// Uncaught exception handler
	public static function onException($exception) {
		$msg = $exception->getMessage() . ' at ' . $exception->getFile() . '#' . $exception->getLine();
		self::$logger->critical(self::removePassword($msg), array('app' => 'PHP'));
	}

	//Recoverable errors handler
	public static function onError($number, $message, $file, $line) {
		if (error_reporting() === 0) {
			return;
		}
		$msg = $message . ' at ' . $file . '#' . $line;
		self::$logger->error(self::removePassword($msg), array('app' => 'PHP'));

	}

	//Recoverable handler which catch all errors, warnings and notices
	public static function onAll($number, $message, $file, $line) {
		$msg = $message . ' at ' . $file . '#' . $line;
		self::$logger->debug(self::removePassword($msg), array('app' => 'PHP'));

	}

}
