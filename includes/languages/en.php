<?php
function lang($phrase)
{
    static $lang = array(
        //homepage 
        'HOME_ADMIN'   =>  'Home',
        'CATEGORIES'    =>  'Categories',
        'ITEMS'         =>  'Items',
        'MEMBERS'       =>  'Members',
        'COMMENTS'       =>  'Comments',
        'STATISTICS'    =>  'Statistics',
        'LOGS'          =>  'Logs',
        'VISIT'         =>  'Visit Shop',
        'EDIT'          =>  'Edit Profile ',
        'SETTINGS'      =>  'Settings',
        'LOGOUT'        =>  'Logout',
        //settings



    );
    return $lang[$phrase];
}
