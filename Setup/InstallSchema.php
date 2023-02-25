<?php
namespace Dulv\SeparateAdminUser\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable("admin_user");

        $setup->getConnection()->addColumn($tableName,'website_id',[
            'type'=>\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'unsigned' => true,
            'nullable' => true,
            'default' => null,
            'comment' => 'the user belong to website'
        ]);
        $setup->getConnection()->addColumn($tableName,'group_id',[
            'type'=>\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'unsigned' => true,
            'nullable' => true,
            'default' => null,
            'comment' => 'the user belong to group'
        ]);
        $setup->getConnection()->addColumn($tableName,'store_id',[
            'type'=>\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            'unsigned' => true,
            'nullable' => true,
            'default' => null,
            'comment' => 'the user belong to store'
        ]);
        $setup->getConnection()->addColumn($tableName,'created_by_id',[
            'type'=>\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            'unsigned' => true,
            'nullable' => true,
            'default' => null,
            'comment' => 'create by user'
        ]);
        $setup->endSetup();
    }
}
