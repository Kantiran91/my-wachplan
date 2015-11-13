<?php

/**
 * Kurze Beschreibung des Datei-Inhalts
 *
 * Lange Beschreibung des Datei-Inhaltes, wenn erforderlich.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT:  $Date$
 * @link    http:/salem.dlrg.de
 **/

require_once '../intern/include/AbstractSettings.inc';
require_once '../init.php';

/**
 * Benutzereinstellungen.
 *
 * Enthält folgende Funktionen:
 * -neuen Benutzer anlegen
 * -Benutzer Bearbeiten
 * -Benutzer löschen
* mögliche Lösung für das Problem das Daten zuerst geholt werden soll, könnten Tabellenüberschriften über alle col darstellen
 */
class UserSettings extends AbstractSettings
{
    const DISPLAYNAME = 'Benutzereinstellungen';

    /**
     * @var String Displayname
     */
    public $displayname = self::DISPLAYNAME;

    /**
     * @var string Titel in der Titelleiste
     */
    public $title = self::DISPLAYNAME;


    public function getFrontend()
    {
        $this->frontend['description'] = 'Folgende Funktionen sind zur Zeit bei den
                    Benutzereinstellungen möglich';
        // add User
        $modulAddUser = static::generateModule('Neuen Benutzer anlegen', 'Form');
        $modulAddUser['content'] = array(
                                    'name'   => 'addUser',
                                    'target' => 'addUser',
                                   );
        $modulAddUser['content']['inputs'] = $this->_getAttributeInputs();
        $this->frontend['moduls'][] = $modulAddUser;
        // change User
        // Hier muss noch ein Formular eingerichtet werden,
        //das den zu bearbeiten Benuzter
        // auswählt.

        /*
         * if ($showUsers) {
         * $userIds = array();
         * $userName = array();
         * foreach ($this->getAllUser() as $user){
         * $userIds[] = $user[0];
         * $userName[] = $user[1];
         * }
         * $inputs[] = array(
         * 'display' => 'Benutzername',
         * 'name' => 'username',
         * 'type' => 'select',
         * 'value' => $userIds,
         * 'valName' => $userName,
         * );
         * ;
         * } else {
         */

        $modulChangeUser = static::generateModule('Benutzer bearbeiten', 'Form');
        $modulChangeUser['content'] = array(
                                       'name'   => 'changeUser',
                                       'target' => 'changeUser',
                                      );
        $modulChangeUser['content']['inputs'] = $this->_getAttributeInputs(TRUE);
        $this->frontend['moduls'][] = $modulChangeUser;

        return json_encode($this->frontend);

    }//end getFrontend()

    /**
     * Erzeugt die Input Elemente die zum hinzufügen und zum ändern notwendig sind.
     *
     * @return array Mehrdim array der Inputs.
     */
    private function  _getAttributeInputs()
    {
        $inputs = array();

        $inputs[] = array(
                     'display' => 'Benutzername',
                     'name'    => 'username',
                     'type'    => 'text',
                    );

        $inputs[] = array(
                     'display' => 'Vorname',
                     'name'    => 'first_name',
                     'type'    => 'text',
                    );
        $inputs[] = array(
                     'display' => 'Nachname',
                     'name'    => 'last_name',
                     'type'    => 'text',
                    );
        $inputs[] = array(
                     'display' => 'eMail',
                     'name'    => 'email',
                     'type'    => 'email',
                    );
        $inputs[] = array(
                     'display' => 'Telefonnummer',
                     'name'    => 'tele',
                     'type'    => 'text',
                    );
        $inputs[] = array(
                     'display' => 'Geburtsdatum',
                     'name'    => 'gb',
                     'type'    => 'date',
                    );
        $inputs[] = array(
                     'display' => 'Abzeichen',
                     'name'    => 'abzeichen',
                     'type'    => 'select',
                     'value'   => array(
                                   'DRSA Bronze',
                                   'DRSA Silber',
                                   'DRSA Gold',
                                  ),
                     'valName' => array(
                                   'DRSA Bronze',
                                   'DRSA Silber',
                                   'DRSA Gold',
                                  ),
                    );
        $inputs[] = array(
                     'display' => 'Ausbildung EH / SAN',
                     'name'    => 'med',
                     'type'    => 'select',
                     'valName' => array(
                                   '',
                                   'EH Kurs',
                                   'San A',
                                   'San B',
                                   'Schulsani',
                                   'Rettungsdienst',
                                  ),
                     'value'   => array(
                                   'eh',
                                   'eh',
                                   'san',
                                   'san',
                                   'san',
                                   'san',
                                  ),
                    );
        $inputs[] = array(
                     'display' => 'Benutzerrechte',
                     'name'    => 'rights',
                     'type'    => 'select',
                     'valName' => array(
                                   'Wachgänger',
                                   'Wachleiter / stellv. Wachleiter',
                                   'Admin',
                                  ),
                     'value'   => array(
                                   '0',
                                   '1',
                                   '2',
                                  ),
                    );

        return $inputs;

    }//end _getAttributeInputs()

    /**
     * Methode um einen Benutzer dem Wachplan hinzuzufügen.
     *
     * @param array $data Der $_POST-Array das durchgereicht wird.
     *
     * @return array  Besteht aus einem Titel und einem Text, der ausgegeben wird.
     */
    public function addUser(array $data)
    {
        global $database;

        //@todo Prüfung auf  Benutzername in Funktion auslagern
        // Prüfen ob der Benutzername schon vergeben ist!
        $query = 'SELECT `id_user`FROM `wp_user` WHERE `user_name`=?';
        $checkQuery = $database->prepare($query);
        $checkQuery->bind_param('s', $_POST['username']);
        $checkQuery->execute();
        $checkQuery->store_result();
        if ($checkQuery->num_rows !== 0) {
            return array('Fehler','Benutzername schon vorhanden.');
        }

        foreach ($data as $value) {
            $value = (string) $value;
            if ($value === '') {
                return array('Fehler', 'Nicht alle Felder ausgefüllt');
            }

            $database->real_escape_string($value);
        }//end foreach

        $newpasswort = static::randomString(10);
        $pwHash = encryptHash($newpasswort);

        // create the db query
        $query = $database->prepare(
            'INSERT INTO
    		`wp_user`(
    		`email`,
    		`user_name`,
    		`hash`,
    		`rights`,
    		`telephone`,
    		`geburtsdatum`,
    		`abzeichen`,
    		`med`,
    		`first_name`,
    		`last_name`
    		)
    		VALUES (?,?,?,?,?,?,?,?,?,?)');
        $query->bind_param(
            'sssissssss',
            $data['email'],
            $data['username'],
            $pwHash,
            $data['rights'],
            $data['tele'],
            $data['gb'],
            $data['abzeichen'],
            $data['med'],
            $data['first_name'],
            $data['last_name']);

            // send as mail with Password
        $mail = new mail(post('email'));
        $keywords = array(
                     'firstName' => post('first_name'),
                     'lastName'  => post('last_name'),
                     'password'  => $newpasswort,
                    );
        $mail->loadTemplate('addUser', $keywords);
        $mail->sendMail();

        $result = array();
        if ($query->execute() === TRUE) {
            $result = array('Meldung', 'Benutzer angelegt!');
        } else {
            $result = array('Fehler', 'Fehler in der Datenbank!');
        }

        return $result;

    }//end addUser()

    /**
     * Gibt eine zufälligen String zurück.
     *
     * @param integer $length Länge des Strings.
     *
     * @return stirng Ein String mit zufälligen Inhalts.
     */
    static function randomString($length = 8)
    {
        // $chars - String aller erlaubten Zahlen
        $chars = '!#_-()%abcdefghijklmnopqrstuvwxyz
    ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        srand(((double) microtime() * 1000000));
        $pass = '';
        for ($i = 1; $i <= $length; $i ++) {
            // Holen eines zufälligen Zeichens
            $num = (rand() % strlen($chars));
            $pass = $pass . substr($chars, $num, 1);
        }

        return $pass;

    }//end randomString()

    /**
     * Schauen ob ich die Iwo anderst hinpacken kann.
     *
     * Lange Beschreibung
     *
     * @return array Liste mit Usern (mit ID und Name)
     */
    private function _getAllUser()
    {
        global $database;
        $stmtUserdata = $database->prepare(
            'SELECT `id_user`, `user_name` FROM `wp_user` ORDER BY `user_name`');
        $stmtUserdata->bind_result($idUser, $userName);
        $stmtUserdata->execute();
        $users = array();
        while ($stmtUserdata->fetch()) {
            $users[] = array(
                        $idUser,
                        $userName,
                       );
        }//end while

        return $users;

    }//end _getAllUser()

}//end class

?>
