<?php

use Phinx\Db\Adapter\PostgresAdapter;
use Phinx\Migration\AbstractMigration;

class CreateDocumentTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $table = $this->table('document', ['id' => false, 'primary_key' => 'id']);

        $table->addColumn('id', PostgresAdapter::PHINX_TYPE_UUID)
            ->addColumn('status', PostgresAdapter::PHINX_TYPE_STRING)
            ->addColumn('payload', PostgresAdapter::PHINX_TYPE_JSON)
            ->addColumn('modify_at', PostgresAdapter::PHINX_TYPE_DATETIME)
            ->addColumn('create_at', PostgresAdapter::PHINX_TYPE_DATETIME);

        $table->create();

    }
}
