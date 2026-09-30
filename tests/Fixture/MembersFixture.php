<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MembersFixture
 */
class MembersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'sort_order' => 1,
                'deleted_at' => '2026-09-30 12:31:19',
                'created' => '2026-09-30 12:31:19',
                'modified' => '2026-09-30 12:31:19',
            ],
        ];
        parent::init();
    }
}
