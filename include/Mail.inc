<?php
/**
 * Enthält die Klasse Mail.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT:$Id$ $Date$
 * @link    http:/salem.dlrg.de
 * @see     Class Mail
 **/

/**
 * Klasse Mail stellt die Schnittstelle zum Mailen dar.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 */
class Mail
{

    /**
     *
     * @var string Email-Adresse des Empfängers.
     */
    private $_empfaenger = '';

    /**
     *
     * @var string Email-Adresse des Senders.
     */
    private $_absender = 'From: DLRG Salem <tl@salem.dlrg.de>';

    /**
     *
     * @var string Betreff der Email
     */
    private $_betreff = '';

    /**
     *
     * @var string Inhalt der Email
     */
    private $_text = '';


    /**
     * Konstruktor.
     *
     * @param string $empfaenger Empfänger der Email.
     * @param string $betreff    Betreff der Email.
     * @todo  Mit RegEx prüfen ob die eMail-Adresse valide ist.
     */
    public function __construct($empfaenger, $betreff = 'Wachplan')
    {
        $this->empfaenger = $empfaenger;
        $this->betreff = $betreff;

    }//end __construct()

    /**
     * Setzt die private Variable $text.
     *
     * @param string $text Text der in die Mail eingefügt werden soll.
     *
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;

    }//end setText()

    /**
     * Läde einen Mailvorlagetext und ersetzt Schlüsselwörter.
     *
     * Mailvorlagen werden mit dem Dateinamen *.mail im Ornder mail_template
     * im Root-Verzeichnis abgelegt.
     * Der Aufbau des Textdokument ist wie folgt:
     * Die erste Zeile enthält den Betreff der Mail. Alle folgenden Zeilen werden
     * als Text der Mail aufgefasst. Um die Mail anpassen zu können, können
     * Schlüsselwörter definiert werden. Dazu wird in der Vorlage an entsprechender
     * Stelle ein Schlüsselwort hinterlegt. Diese wird mit $ vorne und
     * hinten markiert. Das Schlüsselwort und das entsprechende Array sehen
     * dann wie folgt aus:
     * -Schlüsselwort: $tele$
     * -Array = array(tele => "1234/21234" ... )
     *
     * @param string $file  Name des Template das geladen werden soll.
     * @param array  $param Array mit den Attributen die bei der Mail erstetzt
     * werden.
     *
     * @return bool TRUE wenn alles okay ist.
     */
    public function loadTemplate($file, array $param)
    {
        $filename = dirname(__FILE__) . '/../mail_template/'. $file . '.mail';
        $filehandle = fopen($filename, 'r');
        $this->betreff = fgets($filehandle);
        while ($line = fgets($filehandle)) {
            $this->text .= $line;
        }//end while

        foreach ($param as $key => $value) {
            $this->betreff = str_replace(
                '$' . $key . '$',
                $value,
                $this->betreff);
            $this->text = str_replace(
                '$' . $key . '$',
                $value,
                $this->text,
                $count);
        }//end foreach

        return TRUE;

    }//end loadTemplate()

    /**
     * Setzt die private Variable $Betreff.
     *
     * @param string $betreff Setzt den Betreff der Mail neu.
     *
     * @return void
     */
    public function setBetreff($betreff)
    {
        $this->betreff = $betreff;

    }//end setBetreff()

    /**
     * Return the text which is save in the object
     *
     * @return void
     */
    public function getText()
    {
       return $this->text;

    }//end setText()

    /**
     * Return the text of the betreff which is save in the object
     *
     * @return void
     */
    public function getBetreff()
    {
        return $this->betreff;

    }//end setText()

    /**
     * Schickt schließlich die Mail ab.
     *
     * @param string $text Wenn noch nicht über setText erfolgt kann hier die
     * Mail gesendet werden.
     *
     * @return boolean Wenn das Versenden erfolgreich war.
     */
    public function sendMail($text = NULL)
    {
        if (isset($text) === TRUE) {
            $this->text = $text;
        }

        return mail($this->_empfaenger, $this->betreff, $this->text, $this->_absender);

    }//end sendMail()

}//end class

?>