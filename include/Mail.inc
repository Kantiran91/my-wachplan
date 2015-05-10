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

        return mail($this->_empfaenger, $this->_betreff, $this->_text, $this->_absender);

    }//end sendMail()

}//end class

?>