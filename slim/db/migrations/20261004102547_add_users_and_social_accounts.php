<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUsersAndSocialAccounts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('users')
            ->addColumn('name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true])
            ->addTimestamps()
            ->create();

        $this->table('social_accounts')
            ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('provider', 'string', ['limit' => 32])
            ->addColumn('provider_user_id', 'string', ['limit' => 191])
            ->addTimestamps()
            ->addIndex(['provider', 'provider_user_id'], ['unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
