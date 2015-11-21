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
require_once '../init.php';
require_once '../intern/include/AbstractSettings.inc';
require_once '../intern/include/Utils.inc';
require_once '../intern/include/View.inc';

/**
 * Benutzereinstellungen.
 *
 * Enthält folgende Funktionen:
 * -neuen Benutzer anlegen
 * -Benutzer Bearbeiten
 * -Benutzer löschen
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


    /**
     *  Constructor.
     */
    public function __construct()
    {
        View::getInstance()->addScript('usersettings.js', '/wachplan/js/usersettings.js');
        parent::__construct();

    }//end __construct()

    /**
     * Erzeugt das Frontend der Benutzereinstellungen.
     *
     * @return array Mehrdim array der Inputs.
     * @see    AbstractSettings::getFrontend()
     */
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
     * @param boolean $change If true include a select field that show all users.
     *
     * @return array   Mehrdim array der Inputs.
     */
    private function  _getAttributeInputs($change = FALSE)
    {
        $inputs = array();

        if ($change === TRUE) {
            $users = static::getAllUser();
            $userNames = array('');
            $userids   = array('');

            foreach ($users as $user) {
                $userids[] = $user['0'];
                $userNames[] = $user['1'];
            }//end foreach

            $inputs[] = array(
                         'name'  => 'id',
                         'type'  => 'hidden',
                         'value' => '',
                        );

            $inputs[] = array(
                         'display' => 'Benutzer',
                         'name'    => 'user_id',
                         'type'    => 'select',
                         'valName' => $userNames,
                         'value'   => $userids,
                        );
        }//end if

        $attributes = '[
          {
            "display": "Benutzerdaten",
            "type": "header"
          }, {
            "display": "Benutzername",
            "name": "username",
            "type": "text"
          },{
            "display": "Vorname",
            "name": "first_name",
            "type": "text"
          },{
            "display": "Nachname",
            "name": "last_name",
            "type": "text"
          },{
            "display": "eMail",
            "name": "email",
            "type": "email"
          },{
            "display": "Telefonnummer",
            "name": "tele",
            "type": "text"
          },{
            "display": "Geburtsdatum",
            "name": "gb",
            "type": "date"
          },{
            "display": "Qualifkationen",
            "type": "header"
          },{
            "display": "Abzeichen",
            "name": "abzeichen",
            "type": "select",
            "value": ["DRSA Bronze","DRSA Silber","DRSA Gold"],
            "valName": ["DRSA Bronze","DRSA Silber","DRSA Gold"]
          }, {
            "display": "Ausbildung EH / SAN",
            "name": "med",
            "type": "select",
            "valName": ["","EH Kurs","San A","San B","Schulsani","Rettungsdienst"],
            "value": ["","eh","san","san","san","san" ]
          },{
            "display": "Rechte",
            "type": "header"
          },{
            "display": "Benutzerrechte",
            "name": "rights",
            "type": "select",
            "valName": ["Wachgänger", "Wachleiter / stellv. Wachleiter","Admin"],
            "value": ["0","1","2"]
          }]';

        return array_merge($inputs, json_decode($attributes, TRUE));

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
        $database = $GLOBALS['database'];

        $freeUsername = static::checkUsernameFree();
        if ($freeUsername !== TRUE) {
            return $freeUsername;
        }

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
    		VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
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
            $data['last_name']
        );

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
     * Check if the username is not used yet.
     *
     * @return multi True if username is not used else Array of ('Fehler','Message')
     */
    static function checkUsernameFree()
    {
        $database = $GLOBALS['database'];
        $query = 'SELECT `id_user`FROM `wp_user` WHERE `user_name`=?';
        $checkQuery = $database->prepare($query);
        $checkQuery->bind_param('s', $_POST['username']);
        $checkQuery->execute();
        $checkQuery->store_result();
        if ($checkQuery->num_rows !== 0) {
            return array('Fehler', 'Benutzername schon vorhanden.');
        } else {
            return TRUE;
        }

    }//end checkUsernameFree()

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
     * Get List mit allen Useren.
     *
     * Lange Beschreibung
     *
     * @return array Liste mit Usern (mit ID und Name)
     */
    static function getAllUser()
    {
        $database = $GLOBALS['database'];
        $stmtUserdata = $database->prepare(
            'SELECT `id_user`, `user_name` FROM `wp_user` ORDER BY `user_name`'
        );
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

    }//end getAllUser()

}//end class

?>
