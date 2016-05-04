<?php

namespace EWERK\CsPowermailLimit\Hooks;

class FlexFormHook
{
    /**
     * @param array $dataStructArray
     * @param array $conf
     * @param array $row
     * @param string $table
     */
    public function getFlexFormDS_postProcessDS(&$dataStructArray, $conf, $row, $table)
    {
        if ($table === 'tt_content' && $row['CType'] === 'list' && $row['list_type'] === 'powermail_pi1') {
            $dataStructArray['sheets']['extraEntry'] =
                'typo3conf/ext/cs_powermail_limit/Configuration/FlexForms/flexform_powermail_extended.xml';
        }
    }
}