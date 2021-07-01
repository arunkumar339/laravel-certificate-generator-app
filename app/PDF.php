<?php


namespace App;


use setasign\Fpdi\Fpdi;

class PDF extends Fpdi
{

    var $_tplIdx;


    function Header()
    {

        if (is_null($this->_tplIdx)) {

            // THIS IS WHERE YOU GET THE NUMBER OF PAGES
            $this->_tplIdx = $this->importPage(1);

        }
        $this->useTemplate($this->_tplIdx, 0, 0, 297, 210, true);


    }

    function Footer()
    {

    }


}
