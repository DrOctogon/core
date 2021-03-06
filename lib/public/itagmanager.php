<?php
/**
 * @author Bernhard Reiter <ockham@raz.or.at>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Thomas Tanghus <thomas@tanghus.net>
 * @author Vincent Petry <pvince81@owncloud.com>
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
// use OCP namespace for all classes that are considered public.
// This means that they should be used by apps instead of the internal ownCloud classes
namespace OCP;

/**
 * Factory class creating instances of \OCP\ITags
 *
 * A tag can be e.g. 'Family', 'Work', 'Chore', 'Special Occation' or
 * anything else that is either parsed from a vobject or that the user chooses
 * to add.
 * Tag names are not case-sensitive, but will be saved with the case they
 * are entered in. If a user already has a tag 'family' for a type, and
 * tries to add a tag named 'Family' it will be silently ignored.
 */
interface ITagManager {

	/**
	* Create a new \OCP\ITags instance and load tags from db for the current user.
	*
	* @see \OCP\ITags
	* @param string $type The type identifier e.g. 'contact' or 'event'.
	* @param array $defaultTags An array of default tags to be used if none are stored.
	* @param boolean $includeShared Whether to include tags for items shared with this user by others.
	* @param string $userId user for which to retrieve the tags, defaults to the currently
	* logged in user
	* @return \OCP\ITags
	*/
	public function load($type, $defaultTags = array(), $includeShared = false, $userId = null);
}
