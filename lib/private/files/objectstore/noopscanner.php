<?php
/**
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
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
namespace OC\Files\ObjectStore;
use \OC\Files\Cache\Scanner;
use \OC\Files\Storage\Storage;

class NoopScanner extends Scanner {

	public function __construct(Storage $storage) {
		//we don't need the storage, so do nothing here
	}

	/**
	 * scan a single file and store it in the cache
	 *
	 * @param string $file
	 * @param int $reuseExisting
	 * @param bool $parentExistsInCache
	 * @return array with metadata of the scanned file
	 */
	public function scanFile($file, $reuseExisting = 0, $parentExistsInCache = false) {
		return array();
	}

	/**
	 * scan a folder and all it's children
	 *
	 * @param string $path
	 * @param bool $recursive
	 * @param int $reuse
	 * @return array with the meta data of the scanned file or folder
	 */
	public function scan($path, $recursive = self::SCAN_RECURSIVE, $reuse = -1) {
		return array();
	}

	/**
	 * scan all the files and folders in a folder
	 *
	 * @param string $path
	 * @param bool $recursive
	 * @param int $reuse
	 * @return int the size of the scanned folder or -1 if the size is unknown at this stage
	 */
	public function scanChildren($path, $recursive = Storage::SCAN_RECURSIVE, $reuse = -1) {
		$size = 0;
		return $size;
	}

	/**
	 * walk over any folders that are not fully scanned yet and scan them
	 */
	public function backgroundScan() {
		//noop
	}
}
