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

include_once '../intern/include/AbstractSettings.inc';
include_once '../init.php';

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
    var $title = 'Benutzereinstellungen';
    var $displayname = 'Benutzereinstellungen';

    public function __construct(){
        parent::__construct();

    }

    public function getFrontend()
    {
        $this->frontend['description'] = 'Folgende Funktionen sind zur Zeit bei den
                    Benutzereinstellungen möglich';
        // add User
        $modulAddUser = self::generateModule('Neuen Benutzer anlegen', 'Form');
        $modulAddUser['content'] = array(
                                        'name' => 'addUser',
                                        'target' => 'addUser'
        );
        $modulAddUser['content']['inputs'] = $this->getAttributeInputs();
        $this->frontend['moduls'][] = $modulAddUser;
        // change User
        // Hier muss noch ein Formular eingerichtet werden, das den zu bearbeiten Benuzter auswählt.
      /* if ($showUsers) {
            $userIds = array();
            $userName = array();
            foreach ($this->getAllUser() as $user){
                $userIds[] = $user[0];
                $userName[] = $user[1];
            }
            $inputs[] = array(
                'display' => 'Benutzername',
                'name' => 'username',
                'type' => 'select',
                'value' => $userIds,
                'valName' => $userName,
            );
            ;
        } else {*/
        $modulChangeUser = self::generateModule('Benutzer bearbeiten', 'Form');
        $modulChangeUser['content'] = array(
                                        'name' => 'changeUser',
                                        'target' => 'changeUser'
        );
        $modulChangeUser['content']['inputs'] = $this->getAttributeInputs(true);
        $this->frontend['moduls'][] = $modulChangeUser;

        return json_encode($this->frontend);
    }

    private function  getAttributeInputs($showUsers=FALSE){
        $inputs = array();

        $inputs[] = array(
                        'display' => 'Benutzername',
                        'name' => 'username',
                        'type' => 'text'
        );

        $inputs[] = array(
                        'display' => 'Vorname',
                        'name' => 'first_name',
                        'type' => 'text'
        );
        $inputs[] = array(
            'display' => 'Nachname',
            'name' => 'last_name',
            'type' => 'text'
        );
        $inputs[] = array(
            'display' => 'eMail',
            'name' => 'email',
            'type' => 'email'
        );
        $inputs[] = array(
            'display' => 'Telefonnummer',
            'name' => 'tele',
            'type' => 'text'
        );
        $inputs[] = array(
            'display' => 'Geburtsdatum',
            'name' => 'gb',
            'type' => 'date'
        );
        $inputs[] = array(
            'display' => 'Abzeichen',
            'name' => 'abzeichen',
            'type' => 'select',
            'value' => array('DRSA Bronze','DRSA Silber','DRSA Gold'),
            'valName' => array('DRSA Bronze','DRSA Silber','DRSA Gold'),
        );
        $inputs[] = array(
            'display' => 'Ausbildung EH / SAN',
            'name' => 'med',
            'type' => 'select',
            'valName' => array('','EH Kurs','San A','San B','Schulsani','Rettungsdienst'),
            'value' => array('eh','eh','san','san','san','san'),
        );
        $inputs[] = array(
            'display' => 'Benutzerrechte',
            'name' => 'rights',
            'type' => 'select',
            'valName' => array('Wachgänger','Wachleiter / stellv. Wachleiter','Admin'),
            'value' => array('0','1','2'),
        );

        return $inputs;
    }

    public function addUser($data)
    {
        global $database;
        if ($data === NULL || count($data) !== 9) {
            // @todo besser Fehlermldung überlegen
            return array('Fehler', 'Formualar nicht vollständig');
        }
        // Prüfen ob der Benutzername schon vergeben ist!
        $query = 'SELECT `id_user`FROM `wp_user` WHERE `user_name`=?';
        $checkQuery = $database->prepare($query);
        $checkQuery->bind_param('s', $_POST['username']);
        $checkQuery->execute();
        $checkQuery->store_result();
        if ($checkQuery->num_rows !== 0) {

            return array('Fehler', 'Benutzername schon vorhanden.');
        }

        var_dump($data);
        foreach ($data as $value) {
            $value = (string) $value;
            if ($value === '') {
                return array('Fehler', 'Nicht alle Felder ausgefüllt');
            }

            $database->real_escape_string($value);
        }//end foreach

        $newpasswort = self::randomString(10);
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
        $emailAdresse = post('email');
        $betreff = 'Erflogreiche Anmeldung zum Wachplan der Ortsgruppe Salem';
        $mail = new mail($emailAdresse, $betreff);
        $text = 'Hallo ' . post('first_name') . ' ' . post('last_name') . ',';
        $text .= "\n dir wurde von einem der Admins ein Account für den Wachplan
            der Ortsgruppe Salem erstellt.\n Hier kannst du eine Wachtermine
            sehen, denn Daten ändern und dich zusätzlich noch eintragen.
            Solltest du keinen Account brauchen oder nicht zu der DLRG OG Salem
            gehören, melde dich bitte sofort bei uns,
             damit wir das klären können.";
        $text .= "\nFür diesen Wachplan benötigst du ein eigenes Passwort,diese lautet:";
        $text .= " \n\n" . $newpasswort . "\n";
        $text .= "\n---------------------------------------\n";
        $text .= "Viele Grüße \n Deine Technische Leitung";
        $mail->sendMail($text);

        if ($query->execute() === TRUE) {
            return array('Meldung', 'Benutzer angelegt!');
        } else {
            var_dump($query->error_list);
            return array('Fehler', 'Fehler in der Datenbank!');
        }
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

    } // end randomString()


    /**
     * Schauen ob ich die Iwo anderst hinpacken kann.
     *
     * Lange Beschreibung
     * Kommentar.
     */
    private function getAllUser()
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
                            $userName
            );

        }
        return $users;
    }
}
?>