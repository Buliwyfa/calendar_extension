<?php

use yii\db\Migration;

class uninstall extends Migration
{

    public function up()
    {
        $this->dropTable('calendar_extension_calendar');
        $this->dropTable('calendar_extension_calendar_entry');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}