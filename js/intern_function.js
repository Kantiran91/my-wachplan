/**
 * wachplan - javaScript
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @copyright Copyright (c) 2016, Sebastian Friedl
 * @license AGPL-3.0
 */


function change_site(data) {
	$('body').append(data);
}

function change_window(data) {
	window.location = "index.php";
}
