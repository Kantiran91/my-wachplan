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


//TODO Kommentierung verbessern! Damit die Funktionen den Seiten zu geordnet werden können sollte der Pfad mit aufgeschrieben werden siehe datie formulars.js
/** buttons to add yourself */
function add_self(position, day) {
	$.get("include/back_add_acc.php", {
		eingabe : "true",
		position : position,
		day : day
	}, change_window);
}

/** buttons to add somebody */
function add_ac(position, day) {
	$.get("include/back_get_user.php", {
		eingabe : "true",
		position : position,
		day : day
	}, change_site);
}

/** buttons to delete somebody */
function delete_ac(data) {
	$.get("include/back_delete_acc.php", {
		acc : data
	}, change_site);
}

/** buttons to delete self */
function cancel_date(data) {
	$.get("include/back_delete_acc.php", {
		acc : data,
		self : true
	}, change_window);
}

/** buttons to delete user */
function delete_user() {
	tmp = $('#c_id').val();
	$.post("include/back_delete_user.php", {
		id_user :tmp,
	}, change_site);
}

/** button to hide the massage box */
function hide_massage() {
	$('.meldung').hide();
}