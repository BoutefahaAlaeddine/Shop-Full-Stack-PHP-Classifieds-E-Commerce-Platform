<?php
function lang($phrase)
{
    static $lang = array(
        'HOME_ADMIN'     =>  'الصفحة الرئسية',
        'CATEGORIES'    =>  'التصنيفات',
        'ITEMS'         =>  'الاغراض',
        'MEMBERS'       =>  'الاعضاء',
        'STATISTICS'    =>  'احصائيات',
        'LOGS'          =>  'السجلات',
        'VISIT'         =>  'زر متجرنا',
        'EDIT'          =>  'تعديل الملف الشخصي',
        'SETTINGS'      =>  'الاعدادات',
        'LOGOUT'        =>  'الخروج',

    );
    return $lang[$phrase];
}
